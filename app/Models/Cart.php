<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $fillable = ['user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function cartItems() {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    public static function getOrCreate($userId) {
        return self::firstOrCreate(['user_id' => $userId]);
    }

    public function getTotalAttribute() {
        return $this->cartItems->sum(fn($item) => $item->product->price * $item->quantity);
    }
}
