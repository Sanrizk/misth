<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\PlantType;
class PlantTypeSeeder extends Seeder {
    public function run(): void {
        PlantType::truncate();
        PlantType::factory()->count(5)->create();
    }
}
