<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        User::whereHas('role', fn($q) => $q->where('name', 'customer'))
            ->each(function ($user) {
                $cart = Cart::create(['user_id' => $user->id]);
                $products = Product::where('status', 'available')->inRandomOrder()->take(2)->get();
                foreach ($products as $product) {
                    CartItem::create([
                        'cart_id'    => $cart->id,
                        'product_id' => $product->id,
                        'quantity'   => rand(1, 3),
                    ]);
                }
            });
    }
}
