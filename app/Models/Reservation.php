<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Status reservasi yang tersedia
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'table_id',
        'reservation_date',
        'start_time',
        'end_time',
        'guest_count',
        'notes',
        'status',
        'rejection_reason',
        'reservation_code'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reservation_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'guest_count' => 'integer'
    ];

    /**
     * Get status options for reservation
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Konfirmasi',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_COMPLETED => 'Selesai'
        ];
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Get the status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-warning text-dark',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_CANCELLED => 'bg-secondary',
            self::STATUS_COMPLETED => 'bg-info',
            default => 'bg-secondary'
        };
    }

    /**
     * Get the user that owns the reservation
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the table that is reserved
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Scope a query to only include pending reservations
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include approved reservations
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include today's reservations
     */
    public function scopeToday($query)
    {
        return $query->whereDate('reservation_date', today());
    }

    /**
     * Scope a query to only include upcoming reservations
     */
    public function scopeUpcoming($query)
    {
        return $query->where('reservation_date', '>=', today())
                    ->where('status', self::STATUS_APPROVED);
    }
} 