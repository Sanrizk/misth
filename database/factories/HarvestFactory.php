<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Planting;
class HarvestFactory extends Factory {
    public function definition(): array {
        return [
            'planting_id' => Planting::where('status', 'harvested')->inRandomOrder()->first()->id ?? Planting::factory()->create(['status'=>'harvested'])->id,
            'harvest_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'total_yield_quantity' => fake()->numberBetween(20, 200),
            'total_yield_weight' => fake()->randomFloat(2, 5.0, 50.0),
            'quality_grade' => fake()->randomElement(['Grade A', 'Grade B', 'Grade C']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
