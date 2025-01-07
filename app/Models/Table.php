<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Reservation;

class Table extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Status meja yang tersedia
     */
    const STATUS_AVAILABLE = 'available';
    const STATUS_OCCUPIED = 'occupied';
    const STATUS_RESERVED = 'reserved';
    const STATUS_MAINTENANCE = 'maintenance';

    /**
     * Lokasi meja
     */
    const LOCATION_INDOOR = 'indoor';
    const LOCATION_OUTDOOR = 'outdoor';
    const LOCATION_ROOFTOP = 'rooftop';
    const LOCATION_PRIVATE_ROOM = 'private_room';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'table_number',
        'capacity',
        'status',
        'location',
        'description',
        'notes'
    ];

    /**
     * Get array of status options.
     *
     * @return array
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_AVAILABLE => 'Tersedia',
            self::STATUS_OCCUPIED => 'Terisi',
            self::STATUS_RESERVED => 'Direservasi',
            self::STATUS_MAINTENANCE => 'Maintenance'
        ];
    }

    /**
     * Get array of location options.
     *
     * @return array
     */
    public static function getLocationOptions(): array
    {
        return [
            self::LOCATION_INDOOR => 'Dalam Ruangan',
            self::LOCATION_OUTDOOR => 'Luar Ruangan',
            self::LOCATION_ROOFTOP => 'Rooftop',
            self::LOCATION_PRIVATE_ROOM => 'Ruang Privat'
        ];
    }

    /**
     * Get status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Get location label.
     *
     * @return string
     */
    public function getLocationLabelAttribute(): string
    {
        return self::getLocationOptions()[$this->location] ?? $this->location;
    }

    /**
     * Check if table is available.
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Check if table is occupied.
     *
     * @return bool
     */
    public function isOccupied(): bool
    {
        return $this->status === self::STATUS_OCCUPIED;
    }

    /**
     * Check if table is reserved.
     *
     * @return bool
     */
    public function isReserved(): bool
    {
        return $this->status === self::STATUS_RESERVED;
    }

    /**
     * Check if table is under maintenance.
     *
     * @return bool
     */
    public function isUnderMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    /**
     * Scope a query to only include available tables.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope a query to filter by location.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $location
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Scope a query to filter by minimum capacity.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $capacity
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMinCapacity($query, int $capacity)
    {
        return $query->where('capacity', '>=', $capacity);
    }

    /**
     * Get the reservations for the table.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
} 