<?php

namespace App\Models;

use App\Traits\HasUniqueNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasUniqueNumber;

    protected $fillable = [
        'order_id',
        'payment_number',
        'payment_method',
        'status',
        'amount',
        'proof_of_payment',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function generatePaymentNumber(): string
    {
        return static::generateUniqueNumber('PAY', 'payment_number');
    }
} 