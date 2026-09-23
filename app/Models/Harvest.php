<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harvest extends Model
{
    use HasFactory;

    protected $table = 'harvests';

    protected $fillable = [
        'planting_id',
        'harvest_date',
        'total_yield_quantity',
        'total_yield_weight',
        'quality_grade',
        'notes',
    ];

    public function planting()
    {
        return $this->belongsTo(Planting::class, 'planting_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'harvest_id');
    }
}
