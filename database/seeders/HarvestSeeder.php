<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Harvest;
use App\Models\Planting;
use Carbon\Carbon;

class HarvestSeeder extends Seeder
{
    public function run(): void
    {
        Harvest::truncate();

        $plantings = Planting::where('status', 'harvested')->with('plantType')->get();

        foreach ($plantings as $planting) {
            $estDays = $planting->plantType->estimated_harvest_days ?? 30;
            $startDate = Carbon::parse($planting->start_date);
            
            // Panen terjadi sekitar 25 - 45 hari setelah semai, tidak melebihi 8 Nov 2026
            $harvestDate = $startDate->copy()->addDays($estDays + rand(-3, 8));
            $maxDate = Carbon::create(2026, 11, 8);
            if ($harvestDate->greaterThan($maxDate)) {
                $harvestDate = $maxDate->copy()->subDays(rand(1, 3));
            }

            // Bisa terjadi 1 atau 2 kali panen bertahap (batch partial harvest)
            $isPartial = (rand(1, 10) <= 3);

            if ($isPartial) {
                // Panen pertama (sebagian)
                Harvest::create([
                    'planting_id' => $planting->id,
                    'harvest_date' => $harvestDate->copy()->subDays(2)->toDateString(),
                    'total_yield_quantity' => rand(40, 100),
                    'total_yield_weight' => round(rand(8, 20) + (rand(10, 90) / 100), 2),
                    'quality_grade' => 'Grade A',
                    'notes' => 'Panen petik awal kualitas prima',
                ]);

                // Panen kedua (habis)
                Harvest::create([
                    'planting_id' => $planting->id,
                    'harvest_date' => $harvestDate->toDateString(),
                    'total_yield_quantity' => rand(60, 150),
                    'total_yield_weight' => round(rand(12, 30) + (rand(10, 90) / 100), 2),
                    'quality_grade' => (rand(1, 10) <= 7) ? 'Grade A' : 'Grade B',
                    'notes' => 'Panen akhir (panen habis)',
                ]);
            } else {
                Harvest::create([
                    'planting_id' => $planting->id,
                    'harvest_date' => $harvestDate->toDateString(),
                    'total_yield_quantity' => rand(80, 250),
                    'total_yield_weight' => round(rand(15, 50) + (rand(10, 90) / 100), 2),
                    'quality_grade' => (rand(1, 10) <= 6) ? 'Grade A' : ((rand(1, 10) <= 8) ? 'Grade B' : 'Grade C'),
                    'notes' => 'Hasil panen tanaman hidroponik optimal',
                ]);
            }
        }
    }
}
