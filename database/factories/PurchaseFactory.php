<?php

namespace Database\Factories;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => \App\Models\Supplier::factory(),
            'user_id' => 1,
            'purchase_date' => fake()->date(),
            'invoice_number' => 'INV-' . fake()->unique()->numerify('####'),
            'total_amount' => 0,
            'status' => fake()->randomElement(['draft', 'confirmed', 'received', 'cancelled']),
            'notes' => fake()->sentence(),
        ];
    }
}
