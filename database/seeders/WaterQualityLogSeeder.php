<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WaterQualityLog;
use App\Models\Planting;
use Carbon\Carbon;

class WaterQualityLogSeeder extends Seeder
{
    public function run(): void
    {
        WaterQualityLog::truncate();

        $plantings = Planting::all();
        $maxDate = Carbon::create(2026, 11, 8, 17, 0, 0);

        foreach ($plantings as $planting) {
            $startDate = Carbon::parse($planting->start_date);

            // Buat 4 - 6 log kualitas air sepanjang masa tanam
            $logCount = rand(4, 6);

            for ($i = 1; $i <= $logCount; $i++) {
                $checkDate = $startDate->copy()->addDays($i * rand(4, 6))->setTime(rand(6, 17), rand(0, 59));
                if ($checkDate->greaterThan($maxDate)) {
                    $checkDate = $maxDate->copy()->subHours(rand(1, 48));
                }

                // Nilai pH sekitar 5.8 - 6.8 (ideal), sesekali sedikit asam / basa
                $ph = round(rand(56, 72) / 10, 1);
                $ppm = rand(800, 1450);
                $temp = round(rand(240, 295) / 10, 1);

                WaterQualityLog::create([
                    'planting_id' => $planting->id,
                    'checked_at' => $checkDate->toDateTimeString(),
                    'ph_level' => $ph,
                    'tds_ppm' => $ppm,
                    'water_temp' => $temp,
                    'notes' => ($ph >= 6.0 && $ph <= 6.8) ? 'Kondisi air optimal.' : 'Dilakukan penyesuaian pH tandon.',
                ]);
            }
        }
    }
}
