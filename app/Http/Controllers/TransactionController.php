<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'transactionDetails.product'])->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['user', 'transactionDetails.product'])->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:0', // Allowing 0 to filter out easily from form
            'payment_method' => 'required|string',
        ]);

        $hasItems = false;
        foreach ($request->items as $item) {
            if ($item['quantity'] > 0) {
                $hasItems = true;
                break;
            }
        }

        if (!$hasItems) {
            return redirect()->back()->with('error', 'Silakan pilih setidaknya satu produk dengan kuantitas lebih dari 0.')->withInput();
        }

        DB::beginTransaction();
        try {
            $invoiceNumber = 'INV-' . now()->format('YmdHis') . '-' . Auth::id();
            
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'invoice_number' => $invoiceNumber,
                'total_amount' => 0,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $item) {
                if ($item['quantity'] <= 0) continue;
                
                $product = Product::lockForUpdate()->find($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception('Stok tidak mencukupi untuk produk: ' . $product->name);
                }

                $subtotal = $product->price * $item['quantity'];
                
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ]);

                $product->stock -= $item['quantity'];
                if ($product->stock == 0) {
                    $product->status = 'out_of_stock';
                }
                $product->save();

                $totalAmount += $subtotal;
            }

            $transaction->total_amount = $totalAmount;
            $transaction->save();

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipping,completed,cancelled'
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->route('transactions.show', $transaction->id)->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
