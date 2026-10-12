<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'harvest_id',
        'name',
        'price',
        'stock',
        'image_url',
        'description',
        'status',
    ];

    public function getImageUrlAttribute($value): ?string
    {
        // 1. Prioritaskan foto dari Jenis Tanaman jika ada
        $plantTypeImage = optional(optional($this->harvest)->planting)->plantType?->image_url;
        if ($plantTypeImage) {
            return $plantTypeImage;
        }

        // 2. Jika tidak ada foto jenis tanaman, gunakan foto bawaan produk
        if (!empty($value)) {
            if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                return $value;
            }
            return asset('storage/' . $value);
        }

        return null;
    }

    public function harvest()
    {
        return $this->belongsTo(Harvest::class, 'harvest_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'product_id');
    }
}
