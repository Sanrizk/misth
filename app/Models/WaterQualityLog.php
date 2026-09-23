<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterQualityLog extends Model
{
    use HasFactory;

    protected $table = 'water_quality_logs';

    protected $fillable = [
        'planting_id',
        'checked_at',
        'ph_level',
        'tds_ppm',
        'water_temp',
        'notes',
    ];

    public function planting()
    {
        return $this->belongsTo(Planting::class, 'planting_id');
    }
}
