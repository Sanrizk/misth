<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'planting_id',
        'user_id',
        'activity_date',
        'action_type',
        'nutrients_ppm',
        'notes',
    ];

    public function planting(): BelongsTo
    {
        return $this->belongsTo(Planting::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

