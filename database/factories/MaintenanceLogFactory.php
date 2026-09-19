<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Planting;
use App\Models\User;
class MaintenanceLogFactory extends Factory {
    public function definition(): array {
        $petani = User::whereHas('role', function($q){ $q->where('name', 'petani'); })->inRandomOrder()->first();
        return [
            'planting_id' => Planting::inRandomOrder()->first()->id ?? Planting::factory(),
            'user_id' => $petani->id ?? User::factory(),
            'activity_date' => fake()->dateTimeBetween('-2 months', 'now'),
            'action_type' => fake()->randomElement(['Pemberian Nutrisi AB Mix', 'Penyemprotan Pestisida Nabati', 'Pengecekan Akar', 'Pemangkasan Daun']),
            'nutrients_ppm' => fake()->optional()->numberBetween(800, 1500),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
