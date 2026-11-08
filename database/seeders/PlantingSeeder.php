<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Planting;
use App\Models\PlantType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PlantingSeeder extends Seeder
{
    public function run(): void
    {
        Planting::truncate();

        $plantTypes = PlantType::all();
        $petanis = User::whereHas('role', function($q) {
            $q->where('name', 'petani');
        })->get();

        if ($plantTypes->isEmpty() || $petanis->isEmpty()) {
            return;
        }

        // Distribusikan penanaman per bulan dari Januari (01) hingga November (11) 2026
        $batchCounter = 1;

        for ($month = 1; $month <= 11; $month++) {
            // 2-3 penanaman per bulan
            $countPerMonth = ($month === 11) ? 2 : rand(2, 3);

            for ($i = 0; $i < $countPerMonth; $i++) {
                $day = rand(1, ($month === 2 ? 28 : ($month === 11 ? 7 : 28)));
                $startDate = Carbon::create(2026, $month, $day);

                $plantType = $plantTypes->random();
                $petani = $petanis->random();

                // Status logic: penanaman sebelum Oktober kebanyakan harvested, sebagian in_progress / failed
                if ($month <= 9) {
                    $status = (rand(1, 10) <= 8) ? 'harvested' : 'failed';
                } elseif ($month === 10) {
                    $status = (rand(1, 10) <= 6) ? 'harvested' : 'in_progress';
                } else {
                    // November (bulan berjalan) -> in_progress
                    $status = 'in_progress';
                }

                $uniqueStr = Str::upper(Str::random(2)) . rand(10, 99);
                $batchCode = 'BATCH-' . $uniqueStr . '-2026-' . sprintf('%02d', $batchCounter++);

                Planting::create([
                    'plant_type_id' => $plantType->id,
                    'user_id' => $petani->id,
                    'batch_code' => $batchCode,
                    'quantity_seeds' => rand(100, 500),
                    'start_date' => $startDate->toDateString(),
                    'status' => $status,
                ]);
            }
        }
    }
}
