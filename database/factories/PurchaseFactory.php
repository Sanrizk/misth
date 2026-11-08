<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
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
        $persediaanUser = User::whereHas('role', function($q) {
            $q->whereIn('name', ['persediaan', 'admin']);
        })->inRandomOrder()->first();

        return [
            'supplier_id' => Supplier::inRandomOrder()->first()->id ?? Supplier::factory(),
            'user_id' => $persediaanUser->id ?? 1,
            'purchase_date' => fake()->dateTimeBetween('2026-01-01', '2026-11-08')->format('Y-m-d'),
            'invoice_number' => 'INV-2026' . fake()->unique()->numerify('##-###'),
            'total_amount' => 0,
            'status' => fake()->randomElement(['draft', 'confirmed', 'received', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
