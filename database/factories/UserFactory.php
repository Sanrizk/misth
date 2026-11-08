<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;
class UserFactory extends Factory {
    public function definition(): array {
        $username = fake()->unique()->userName();
        return [
            'role_id' => Role::inRandomOrder()->first()->id ?? Role::factory(),
            'name' => fake()->name(),
            'email' => $username . '@misth.my.id',
            'password' => bcrypt('password'),
            'phone' => fake()->phoneNumber(),
        ];
    }
}
