<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Makanan',
                'icon' => 'fa fa-hamburger'
            ],
            [
                'name' => 'Minuman',
                'icon' => 'fa fa-coffee'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 