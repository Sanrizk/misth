<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('store.cart.index');

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $paymentMethods = ['Transfer Bank', 'COD', 'QRIS', 'Dompet Digital'];
        $cartCount = collect($cart)->sum('quantity');

        return view('store.checkout', compact('cart', 'total', 'paymentMethods', 'cartCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('store.index');

        return DB::transaction(function () use ($request, $cart) {
            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi.");
                }
            }

            $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
            $transaction = Transaction::create([
                'user_id'        => Auth::id(),
                'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . Auth::id(),
                'total_amount'   => $total,
                'status'         => 'pending',
                'payment_method' => $request->payment_method,
            ]);

            foreach ($cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['product_id'],
                    'quantity'       => $item['quantity'],
                    'subtotal'       => $item['price'] * $item['quantity'],
                ]);

                $product = Product::findOrFail($item['product_id']);
                $product->decrement('stock', $item['quantity']);
                if ($product->stock <= 0) {
                    $product->update(['status' => 'out_of_stock']);
                }
            }

            session()->forget('cart');

            return redirect()->route('store.checkout.success', $transaction->id);
        });
    }

    public function success(Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);
        $transaction->load('transactionDetails.product');
        $cartCount = collect(session('cart', []))->sum('quantity');
        return view('store.checkout-success', compact('transaction', 'cartCount'));
    }
}
