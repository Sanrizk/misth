<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Planting;
class PlantingSeeder extends Seeder {
    public function run(): void {
        Planting::truncate();
        Planting::factory()->count(15)->create();
    }
}
