<?php

namespace Database\Factories;

use App\Models\MaterialUsage;
use App\Models\Material;
use App\Models\MaintenanceLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialUsageFactory extends Factory
{
    protected $model = MaterialUsage::class;

    public function definition(): array
    {
        return [
            'maintenance_log_id' => MaintenanceLog::inRandomOrder()->first()->id,
            'material_id' => Material::inRandomOrder()->first()->id,
            'quantity_used' => fake()->randomFloat(2, 0.1, 10),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
