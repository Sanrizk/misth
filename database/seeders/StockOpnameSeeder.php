<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockAdjustment;
use App\Models\Material;
use App\Models\User;
use Carbon\Carbon;

class StockOpnameSeeder extends Seeder
{
    public function run(): void
    {
        StockAdjustment::truncate();

        $materials = Material::all();
        $persediaanUser = User::whereHas('role', function($q) {
            $q->whereIn('name', ['persediaan', 'admin']);
        })->first() ?? User::first();

        if ($materials->isEmpty()) {
            return;
        }

        $adjSeq = 1;

        // Rutin stock opname setiap bulan dari Januari hingga November 2026
        for ($month = 1; $month <= 11; $month++) {
            // Setiap bulan ada 1-3 penyesuaian fisik / opname
            $adjCount = ($month === 11) ? 2 : rand(1, 3);

            for ($a = 0; $a < $adjCount; $a++) {
                $day = rand(15, ($month === 2 ? 28 : ($month === 11 ? 7 : 28)));
                $date = Carbon::create(2026, $month, $day);

                $mat = $materials->random();
                $type = (rand(1, 10) <= 6) ? 'out' : 'in'; // Lebih sering penyusutan/rusak dibanding lebih
                $quantity = round(rand(5, 50) / 10, 2); // 0.5 - 5.0

                $stockBefore = (float) $mat->stock;
                if ($type === 'in') {
                    $stockAfter = $stockBefore + $quantity;
                    $reason = fake()->randomElement(['Selisih Lebih Fisik', 'Bonus Pembelian Vendor', 'Koreksi Data Perhitungan']);
                } else {
                    $quantity = min($quantity, max(0.5, $stockBefore));
                    $stockAfter = max(0, $stockBefore - $quantity);
                    $reason = fake()->randomElement(['Selisih Kurang Fisik', 'Kemasan Bocor / Rusak', 'Kadaluarsa / Menggumpal', 'Tumpah Saat Penuangan']);
                }

                // Update stok riil bahan
                $mat->stock = $stockAfter;
                $mat->save();

                $adjNumber = 'ADJ-' . $date->format('Ymd') . '-' . sprintf('%03d', $adjSeq++);

                StockAdjustment::create([
                    'adjustment_number' => $adjNumber,
                    'material_id' => $mat->id,
                    'user_id' => $persediaanUser->id ?? null,
                    'adjustment_date' => $date->toDateString(),
                    'type' => $type,
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reason' => $reason,
                    'notes' => 'Pemeriksaan stok fisik berkala bulan ' . $date->translatedFormat('F Y'),
                ]);
            }
        }
    }
}
