<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Harvest;
use App\Models\Planting;
class HarvestSeeder extends Seeder {
    public function run(): void {
        Harvest::truncate();
        $plantings = Planting::where('status', 'harvested')->get();
        foreach($plantings as $planting) {
            Harvest::factory()->create(['planting_id' => $planting->id]);
        }
    }
}
