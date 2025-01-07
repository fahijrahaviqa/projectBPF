<?php

namespace App\Models;

use App\Traits\HasUniqueNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasUniqueNumber;

    protected $fillable = [
        'order_number',
        'user_id',
        'is_drive_thru',
        'status',
        'total_amount',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'is_drive_thru' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function deliveryAddress(): HasOne
    {
        return $this->hasOne(DeliveryAddress::class);
    }

    public static function generateOrderNumber(): string
    {
        return static::generateUniqueNumber('ORD', 'order_number');
    }

    /**
     * Get the testimonial for the order.
     */
    public function testimonial()
    {
        return $this->hasOne(Testimonial::class);
    }

    /**
     * Check if order has testimonial.
     */
    public function hasTestimonial(): bool
    {
        return $this->testimonial()->exists();
    }

    /**
     * Check if order can be reviewed.
     */
    public function canBeReviewed(): bool
    {
        return $this->status === 'completed' && !$this->hasTestimonial();
    }
} 