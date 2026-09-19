<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\MaintenanceLog;
use App\Models\Planting;
class MaintenanceLogSeeder extends Seeder {
    public function run(): void {
        MaintenanceLog::truncate();
        $plantings = Planting::all();
        foreach($plantings as $planting) {
            MaintenanceLog::factory()->count(3)->create(['planting_id' => $planting->id]);
        }
    }
}
