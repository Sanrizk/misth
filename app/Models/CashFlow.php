<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_number',
        'transaction_date',
        'type',
        'category',
        'amount',
        'user_id',
        'reference_type',
        'reference_id',
        'description',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'reference_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'reference_id');
    }

    /**
     * Cek apakah ini transaksi otomatis dari sistem
     */
    public function getIsAutomaticAttribute(): bool
    {
        return in_array($this->reference_type, ['order', 'purchase']);
    }
}

