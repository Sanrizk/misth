<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlantType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'estimated_harvest_days',
        'description',
    ];

    public function plantings(): HasMany
    {
        return $this->hasMany(Planting::class);
    }
}

