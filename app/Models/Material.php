<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';
    
    protected $fillable = [
        'code', 'name', 'category', 'unit',
        'stock', 'min_stock', 'price_per_unit',
        'description', 'status'
    ];

    public function materialUsages() {
        return $this->hasMany(MaterialUsage::class, 'material_id');
    }

    // Accessor: cek apakah stok di bawah minimum
    public function getIsLowStockAttribute() {
        return $this->stock <= $this->min_stock;
    }
}
