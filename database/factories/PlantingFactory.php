<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PlantType;
use App\Models\User;
use Illuminate\Support\Str;

class PlantingFactory extends Factory {
    public function definition(): array {
        $petani = User::whereHas('role', function($q){ $q->where('name', 'petani'); })->inRandomOrder()->first();
        $uniqueStr = Str::upper(fake()->bothify('??##'));
        
        return [
            'plant_type_id' => PlantType::inRandomOrder()->first()->id ?? PlantType::factory(),
            'user_id' => $petani->id ?? User::factory(),
            'batch_code' => 'BATCH-' . $uniqueStr . '-2026-' . fake()->numberBetween(1, 99),
            'quantity_seeds' => fake()->numberBetween(50, 500),
            'start_date' => fake()->dateTimeBetween('2026-01-01', '2026-11-08')->format('Y-m-d'),
            'status' => fake()->randomElement(['in_progress', 'harvested', 'failed']),
        ];
    }
}
