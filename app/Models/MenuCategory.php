<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuCategory extends Model
{
    protected $fillable = [
        'menu_item_id',
        'category_id'
    ];

    /**
     * Relasi dengan MenuItem
     */
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    /**
     * Relasi dengan Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
} 