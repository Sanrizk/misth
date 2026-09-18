<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Harvest extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = [
        'planting_id',
        'harvest_date',
        'total_yield_quantity',
        'total_yield_weight',
        'quality_grade',
        'notes',
    ];

    public function planting(): BelongsTo
    {
        return $this->belongsTo(Planting::class);
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }
}

