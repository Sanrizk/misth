<?php

namespace Database\Factories;

use App\Models\StockAdjustment;
use App\Models\Material;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockAdjustment>
 */
class StockAdjustmentFactory extends Factory
{
    protected $model = StockAdjustment::class;

    public function definition(): array
    {
        $persediaanUser = User::whereHas('role', function($q) {
            $q->whereIn('name', ['persediaan', 'admin']);
        })->inRandomOrder()->first();

        $material = Material::inRandomOrder()->first() ?? Material::factory()->create();
        $type = fake()->randomElement(['in', 'out']);
        $quantity = fake()->randomFloat(2, 0.5, 15);
        $stockBefore = (float) $material->stock;
        
        if ($type === 'in') {
            $stockAfter = $stockBefore + $quantity;
            $reason = fake()->randomElement(['Selisih Lebih Fisik', 'Bonus Pembelian', 'Pengembalian Sisa Operasional', 'Koreksi Data']);
        } else {
            $stockAfter = max(0, $stockBefore - $quantity);
            $reason = fake()->randomElement(['Selisih Kurang Fisik', 'Bahan Rusak / Bocor', 'Kadaluarsa', 'Kemasan Robek / Susut', 'Tumpah']);
        }

        $date = fake()->dateTimeBetween('2026-01-01', '2026-11-08');

        return [
            'adjustment_number' => 'ADJ-' . $date->format('Ymd') . '-' . strtoupper(fake()->bothify('??##')),
            'material_id' => $material->id,
            'user_id' => $persediaanUser->id ?? null,
            'adjustment_date' => $date->format('Y-m-d'),
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reason' => $reason,
            'notes' => fake()->optional()->sentence(6),
        ];
    }
}
