<?php

namespace Database\Factories;

use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition(): array
    {
        return [
            'code' => 'MAT-' . str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'name' => fake()->randomElement([
                'AB Mix Part A', 'AB Mix Part B', 'Pestisida Nabati', 
                'Nutrisi Kalsium', 'Kapur Dolomit', 'Rockwool', 
                'Net Pot', 'Selang Hidroponik', 'pH Up', 'pH Down'
            ]),
            'category' => fake()->randomElement(['nutrient', 'pesticide', 'operational']),
            'unit' => fake()->randomElement(['liter', 'kg', 'botol', 'pcs', 'gram']),
            'stock' => fake()->randomFloat(2, 0, 100),
            'min_stock' => fake()->randomFloat(2, 5, 20),
            'price_per_unit' => fake()->randomElement([15000, 25000, 50000, 75000, 100000]),
            'description' => fake()->optional()->sentence(),
            'status' => 'active',
        ];
    }
}
