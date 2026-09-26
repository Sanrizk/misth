<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceLog;
use App\Models\MaterialUsage;
use App\Models\Material;

class MaterialUsageSeeder extends Seeder
{
    public function run(): void
    {
        MaintenanceLog::all()->each(function ($log) {
            MaterialUsage::factory(2)->create([
                'maintenance_log_id' => $log->id,
                'material_id' => Material::inRandomOrder()->first()->id,
            ]);
        });
    }
}
