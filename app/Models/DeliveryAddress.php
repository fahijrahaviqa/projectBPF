<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryAddress extends Model
{
    protected $fillable = [
        'order_id',
        'recipient_name',
        'recipient_phone',
        'address',
        'postal_code',
        'delivery_instructions'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
} 