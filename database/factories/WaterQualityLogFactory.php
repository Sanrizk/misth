<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Planting;
class WaterQualityLogFactory extends Factory {
    public function definition(): array {
        return [
            'planting_id' => Planting::inRandomOrder()->first()->id ?? Planting::factory(),
            'checked_at' => fake()->dateTimeBetween('-2 months', 'now'),
            'ph_level' => fake()->randomFloat(1, 5.5, 7.0),
            'tds_ppm' => fake()->numberBetween(800, 1500),
            'water_temp' => fake()->randomFloat(1, 24.0, 30.0),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
