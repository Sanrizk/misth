<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
class TransactionFactory extends Factory {
    public function definition(): array {
        $customer = User::whereHas('role', function($q){ $q->where('name', 'customer'); })->inRandomOrder()->first();
        return [
            'user_id' => $customer->id ?? User::factory(),
            'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . fake()->unique()->numberBetween(1000, 99999),
            'total_amount' => 0,
            'status' => fake()->randomElement(['pending', 'terbayarkan', 'batal']),
            'payment_method' => fake()->randomElement(['Transfer Bank', 'COD', 'QRIS', 'Dompet Digital']),
        ];
    }
}
