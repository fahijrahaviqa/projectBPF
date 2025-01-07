<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'icon'
    ];

    /**
     * Relasi many-to-many dengan MenuItem
     */
    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class, 'menu_categories')
                    ->withTimestamps();
    }
} 