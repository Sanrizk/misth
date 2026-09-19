<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;
use App\Models\Product;
class TransactionDetailFactory extends Factory {
    public function definition(): array {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $qty = fake()->numberBetween(1, 5);
        return [
            'transaction_id' => Transaction::inRandomOrder()->first()->id ?? Transaction::factory(),
            'product_id' => $product->id,
            'quantity' => $qty,
            'subtotal' => $qty * $product->price,
        ];
    }
}
