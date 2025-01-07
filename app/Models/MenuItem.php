<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MenuItem extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'image'
    ];

    /**
     * Cast atribut ke tipe data yang sesuai
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Relasi many-to-many dengan Category
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'menu_categories')
                    ->withTimestamps();
    }
} 