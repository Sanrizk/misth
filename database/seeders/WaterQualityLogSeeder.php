<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\WaterQualityLog;
use App\Models\Planting;
class WaterQualityLogSeeder extends Seeder {
    public function run(): void {
        WaterQualityLog::truncate();
        $plantings = Planting::all();
        foreach($plantings as $planting) {
            WaterQualityLog::factory()->count(5)->create(['planting_id' => $planting->id]);
        }
    }
}
