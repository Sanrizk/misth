<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class PlantTypeFactory extends Factory {
    public function definition(): array {
        return [
            'name' => fake()->randomElement(['Selada Keriting', 'Pakcoy', 'Bayam Merah', 'Kangkung', 'Sawi Hijau']),
            'estimated_harvest_days' => fake()->randomElement([25, 30, 35, 40]),
            'description' => fake()->sentence(),
        ];
    }
}
