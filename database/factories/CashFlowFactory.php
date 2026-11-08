<?php

namespace Database\Factories;

use App\Models\CashFlow;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CashFlow>
 */
class CashFlowFactory extends Factory
{
    protected $model = CashFlow::class;

    public function definition(): array
    {
        $keuanganUser = User::whereHas('role', function($q) {
            $q->whereIn('name', ['keuangan', 'admin']);
        })->inRandomOrder()->first();

        $type = fake()->randomElement(['in', 'out']);
        $category = $type === 'in' 
            ? fake()->randomElement(['Penjualan Panen', 'Penjualan Bibit', 'Investasi / Modal', 'Subsidi / Hibah', 'Pendapatan Lain-lain'])
            : fake()->randomElement(['Listrik & Air', 'Gaji & Upah Petani', 'Pemeliharaan Greenhouse', 'BBM & Transportasi Operasional', 'Kemasan & Packaging', 'Konsumsi & Logistik']);

        $date = fake()->dateTimeBetween('2026-01-01', '2026-11-08');

        return [
            'cash_number' => 'KAS-' . $date->format('Ymd') . '-' . strtoupper(fake()->bothify('??##')),
            'transaction_date' => $date->format('Y-m-d'),
            'type' => $type,
            'category' => $category,
            'amount' => fake()->randomElement([
                150000, 250000, 500000, 750000, 1000000, 1500000, 2000000, 3500000, 5000000
            ]),
            'user_id' => $keuanganUser->id ?? null,
            'reference_type' => 'manual',
            'reference_id' => null,
            'description' => fake()->sentence(8),
        ];
    }
}
