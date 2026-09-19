<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Harvest;
class ProductSeeder extends Seeder {
    public function run(): void {
        Product::truncate();
        $harvests = Harvest::all();
        foreach($harvests as $harvest) {
            $plantName = $harvest->planting->plantType->name ?? 'Tanaman';
            Product::factory()->create([
                'harvest_id' => $harvest->id,
                'name' => $plantName . ' Segar Hidroponik',
                'stock' => $harvest->total_yield_quantity,
            ]);
        }
    }
}
