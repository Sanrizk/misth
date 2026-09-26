<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
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
        $cart = Cart::getOrCreate(Auth::id());
        $cart->load('cartItems.product');

        if ($cart->cartItems->isEmpty()) {
            return redirect()->route('store.cart.index')
                             ->with('error', 'Keranjang masih kosong.');
        }

        $total = $cart->total;
        $paymentMethods = ['Transfer Bank', 'COD', 'QRIS', 'Dompet Digital'];

        return view('store.checkout', compact('cart', 'total', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $cart = Cart::getOrCreate(Auth::id());
        $cart->load('cartItems.product');

        if ($cart->cartItems->isEmpty()) {
            return redirect()->route('store.index');
        }

        return DB::transaction(function () use ($request, $cart) {
            foreach ($cart->cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Stok {$item->product->name} tidak mencukupi.");
                }
            }

            $transaction = Transaction::create([
                'user_id'        => Auth::id(),
                'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . Auth::id(),
                'total_amount'   => $cart->total,
                'status'         => 'pending',
                'payment_method' => $request->payment_method,
            ]);

            foreach ($cart->cartItems as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item->product_id,
                    'quantity'       => $item->quantity,
                    'subtotal'       => $item->subtotal,
                ]);

                $product = Product::findOrFail($item->product_id);
                $product->decrement('stock', $item->quantity);
                if ($product->stock <= 0) {
                    $product->update(['status' => 'out_of_stock']);
                }
            }

            $cart->cartItems()->delete();

            return redirect()->route('store.checkout.success', $transaction->id);
        });
    }

    public function success(Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);
        $transaction->load('transactionDetails.product');
        return view('store.checkout-success', compact('transaction'));
    }
}
