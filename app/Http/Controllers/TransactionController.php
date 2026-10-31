<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\CashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'transactionDetails.product'])->latest();

        if ($request->search) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(10);
        
        if ($request->wantsJson()) {
            return response()->json($transactions);
        }

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

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,terbayarkan,batal',
        ]);

        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        DB::transaction(function () use ($transaction, $oldStatus, $newStatus) {
            // Jika dibatalkan, kembalikan stok
            if ($newStatus === 'batal' && $oldStatus !== 'batal') {
                foreach ($transaction->transactionDetails as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        $product->increment('stock', $detail->quantity);
                        if ($product->status === 'out_of_stock') {
                            $product->update(['status' => 'available']);
                        }
                    }
                }
            }

            $transaction->update(['status' => $newStatus]);

            // Sinkronisasi Kas Otomatis
            if ($newStatus === 'terbayarkan' && $oldStatus !== 'terbayarkan') {
                // Catat kas masuk jika belum ada
                CashFlow::firstOrCreate(
                    [
                        'reference_type' => 'order',
                        'reference_id' => $transaction->id,
                    ],
                    [
                        'cash_number' => 'KAS-ORD-' . $transaction->invoice_number,
                        'transaction_date' => now()->toDateString(),
                        'type' => 'in',
                        'category' => 'Penjualan Produk',
                        'amount' => $transaction->total_amount,
                        'user_id' => Auth::id(),
                        'description' => 'Pendapatan Pesanan ' . $transaction->invoice_number . ' (Pelanggan: ' . (optional($transaction->user)->name ?? '-') . ')',
                    ]
                );
            } elseif ($newStatus === 'batal' && $oldStatus === 'terbayarkan') {
                // Hapus kas masuk jika dibatalkan
                CashFlow::where('reference_type', 'order')
                    ->where('reference_id', $transaction->id)
                    ->delete();
            }
        });

        return redirect()->route('transactions.index')
                         ->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function findForScanner(Request $request)
    {
        $request->validate(['invoice_number' => 'required|string']);

        $transaction = Transaction::with(['user', 'transactionDetails.product'])
            ->where('invoice_number', $request->invoice_number)
            ->first();

        if (!$transaction) {
            return response()->json(['found' => false, 'message' => 'Transaksi tidak ditemukan.']);
        }

        // Format data for frontend
        $data = [
            'id' => $transaction->id,
            'invoice_number' => $transaction->invoice_number,
            'status' => $transaction->status,
            'total_formatted' => 'Rp ' . number_format($transaction->total_amount, 0, ',', '.'),
            'created_at' => $transaction->created_at->format('d M Y H:i'),
            'customer' => [
                'name' => optional($transaction->user)->name,
                'phone' => optional($transaction->user)->phone,
            ],
            'items' => $transaction->transactionDetails->map(function ($detail) {
                return [
                    'name' => optional($detail->product)->name,
                    'quantity' => $detail->quantity,
                    'subtotal' => 'Rp ' . number_format($detail->subtotal, 0, ',', '.'),
                ];
            }),
        ];

        return response()->json([
            'found' => true,
            'transaction' => $data,
        ]);
    }

    public function confirmForScanner(Request $request, Transaction $transaction)
    {
        $request->validate(['status' => 'required|in:terbayarkan,batal']);

        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Status transaksi ini sudah tidak dapat diubah.',
            ], 400);
        }

        if ($request->status === 'batal') {
            DB::transaction(function () use ($transaction, $request) {
                foreach ($transaction->transactionDetails as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        $product->increment('stock', $detail->quantity);
                        if ($product->status === 'out_of_stock') {
                            $product->update(['status' => 'available']);
                        }
                    }
                }
                $transaction->update(['status' => $request->status]);

                // Hapus kas jika ada
                CashFlow::where('reference_type', 'order')
                    ->where('reference_id', $transaction->id)
                    ->delete();
            });
            $message = 'Pesanan berhasil dibatalkan.';
        } else {
            DB::transaction(function () use ($transaction) {
                $transaction->update(['status' => 'terbayarkan']);

                CashFlow::firstOrCreate(
                    [
                        'reference_type' => 'order',
                        'reference_id' => $transaction->id,
                    ],
                    [
                        'cash_number' => 'KAS-ORD-' . $transaction->invoice_number,
                        'transaction_date' => now()->toDateString(),
                        'type' => 'in',
                        'category' => 'Penjualan Produk',
                        'amount' => $transaction->total_amount,
                        'user_id' => Auth::id(),
                        'description' => 'Pendapatan Pesanan ' . $transaction->invoice_number . ' via Scanner',
                    ]
                );
            });
            $message = 'Pembayaran berhasil dikonfirmasi.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}
