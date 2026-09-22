<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Planting extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_type_id',
        'user_id',
        'batch_code',
        'quantity_seeds',
        'start_date',
        'status',
    ];
    
    protected $casts = [
        'start_date' => 'datetime',
    ];


    public function plantType(): BelongsTo
    {
        return $this->belongsTo(PlantType::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function waterQualityLogs(): HasMany
    {
        return $this->hasMany(WaterQualityLog::class);
    }

    public function harvest(): HasOne
    {
        return $this->hasOne(Harvest::class);
    }
}

