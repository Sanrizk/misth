<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceLog;
use App\Models\Planting;
use App\Models\User;
use Carbon\Carbon;

class MaintenanceLogSeeder extends Seeder
{
    public function run(): void
    {
        MaintenanceLog::truncate();

        $plantings = Planting::all();
        $petanis = User::whereHas('role', function($q) {
            $q->where('name', 'petani');
        })->get();

        $actions = [
            'Pemberian Nutrisi AB Mix',
            'Penyemprotan Pestisida Nabati',
            'Pembersihan Talang & Pengecekan Akar',
            'Pemangkasan Daun Kuning & Perapihan Netpot',
            'Penambahan Volume Air Tandon'
        ];

        foreach ($plantings as $planting) {
            $startDate = Carbon::parse($planting->start_date);
            $maxDate = Carbon::create(2026, 11, 8, 17, 0, 0);

            // Buat 3 - 5 log perawatan bertahap sepanjang masa tanam
            $logCount = rand(3, 5);

            for ($i = 1; $i <= $logCount; $i++) {
                $logDate = $startDate->copy()->addDays($i * rand(5, 7))->setTime(rand(7, 16), rand(0, 59));
                if ($logDate->greaterThan($maxDate)) {
                    $logDate = $maxDate->copy()->subHours(rand(1, 48));
                }

                $petani = $petanis->isNotEmpty() ? $petanis->random() : User::first();
                $action = $actions[array_rand($actions)];

                MaintenanceLog::create([
                    'planting_id' => $planting->id,
                    'user_id' => $petani->id ?? 1,
                    'activity_date' => $logDate->toDateTimeString(),
                    'action_type' => $action,
                    'nutrients_ppm' => rand(850, 1400),
                    'notes' => 'Perawatan rutin ' . strtolower($action) . ' berjalan lancar.',
                ]);
            }
        }
    }
}
