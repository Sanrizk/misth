<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::getOrCreate(Auth::id());
        $cart->load('cartItems.product.harvest.planting.plantType');
        $total = $cart->total;

        return view('store.cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = Cart::getOrCreate(Auth::id());

        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity + $request->quantity;
            if ($newQty > $product->stock) {
                return back()->with('error', 'Jumlah melebihi stok tersedia.');
            }
            $cartItem->update(['quantity' => $newQty]);
        } else {
            if ($product->stock < $request->quantity) {
                return back()->with('error', 'Stok tidak mencukupi.');
            }
            $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity'     => 'required|integer|min:0',
        ]);

        $cartItem = CartItem::findOrFail($request->cart_item_id);

        abort_if($cartItem->cart->user_id !== Auth::id(), 403);

        if ($request->quantity <= 0) {
            $cartItem->delete();
        } else {
            if ($request->quantity > $cartItem->product->stock) {
                return back()->with('error', 'Jumlah melebihi stok tersedia.');
            }
            $cartItem->update(['quantity' => $request->quantity]);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        abort_if($cartItem->cart->user_id !== Auth::id(), 403);
        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear()
    {
        $cart = Cart::getOrCreate(Auth::id());
        $cart->cartItems()->delete();

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
