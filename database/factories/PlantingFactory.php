<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PlantType;
use App\Models\User;
class PlantingFactory extends Factory {
    public function definition(): array {
        $petani = User::whereHas('role', function($q){ $q->where('name', 'petani'); })->inRandomOrder()->first();
        return [
            'plant_type_id' => PlantType::inRandomOrder()->first()->id ?? PlantType::factory(),
            'user_id' => $petani->id ?? User::factory(),
            'batch_code' => 'BATCH-' . strtoupper(fake()->bothify('??##')) . '-' . now()->year . '-' . fake()->numberBetween(1, 99),
            'quantity_seeds' => fake()->numberBetween(50, 500),
            'start_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'status' => fake()->randomElement(['in_progress', 'harvested', 'failed']),
        ];
    }
}
