<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterQualityLog extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = [
        'planting_id',
        'checked_at',
        'ph_level',
        'tds_ppm',
        'water_temp',
        'notes',
    ];

    public function planting(): BelongsTo
    {
        return $this->belongsTo(Planting::class);
    }
}

