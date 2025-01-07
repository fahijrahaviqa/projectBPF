<?php

namespace App\Traits;

trait HasUniqueNumber
{
    public static function generateUniqueNumber(string $prefix, string $field): string
    {
        $date = now()->format('Ymd');
        $sequence = 1;

        do {
            $number = $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            $exists = static::where($field, $number)->exists();
            $sequence++;
        } while ($exists);

        return $number;
    }
} 