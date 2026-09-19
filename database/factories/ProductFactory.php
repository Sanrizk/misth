<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Harvest;
class ProductFactory extends Factory {
    public function definition(): array {
        $harvest = Harvest::inRandomOrder()->first() ?? Harvest::factory()->create();
        $plantName = $harvest->planting->plantType->name ?? 'Tanaman';
        return [
            'harvest_id' => $harvest->id,
            'name' => $plantName . ' Segar Hidroponik',
            'price' => fake()->randomElement([15000, 20000, 25000, 30000]),
            'stock' => $harvest->total_yield_quantity,
            'image_url' => fake()->imageUrl(400, 400, 'food', true),
            'description' => fake()->sentence(),
            'status' => 'available',
        ];
    }
}
