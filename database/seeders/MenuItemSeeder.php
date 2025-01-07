<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $makanan = Category::where('name', 'Makanan')->first();
        $minuman = Category::where('name', 'Minuman')->first();

        // Menu Makanan
        $menuMakanan = [
            [
                'name' => 'Bakso Jumbo',
                'price' => 22000,
                'description' => 'Bakso jumbo dengan daging pilihan berkualitas',
                'image' => 'menu-images/menu-1.jpg'
            ],
            [
                'name' => 'Pangsit Bakso Telur',
                'price' => 18000,
                'description' => 'Pangsit bakso dengan telur yang lezat',
                'image' => 'menu-images/menu-2.jpg'
            ],
            [
                'name' => 'Bakso Urat',
                'price' => 13000,
                'description' => 'Bakso urat yang kenyal dan gurih',
                'image' => 'menu-images/menu-3.jpg'
            ],
            [
                'name' => 'Pangsit Urat + Telur',
                'price' => 20000,
                'description' => 'Kombinasi pangsit urat dan telur yang nikmat',
                'image' => 'menu-images/menu-4.jpg'
            ],
            [
                'name' => 'Bakso Telur',
                'price' => 17000,
                'description' => 'Bakso dengan telur yang spesial',
                'image' => 'menu-images/menu-5.jpg'
            ],
            [
                'name' => 'Pangsit Bakso Jumbo',
                'price' => 22000,
                'description' => 'Pangsit dengan bakso jumbo yang mengenyangkan',
                'image' => 'menu-images/menu-6.jpg'
            ],
            [
                'name' => 'Bakso Urat+Telur',
                'price' => 20000,
                'description' => 'Kombinasi bakso urat dan telur yang lezat',
                'image' => 'menu-images/menu-1.jpg'
            ],
            [
                'name' => 'Pangsit Ayam',
                'price' => 13000,
                'description' => 'Pangsit dengan isian ayam yang gurih',
                'image' => 'menu-images/menu-2.jpg'
            ],
            [
                'name' => 'Mie Ayam',
                'price' => 13000,
                'description' => 'Mie ayam dengan topping ayam yang melimpah',
                'image' => 'menu-images/menu-3.jpg'
            ],
            [
                'name' => 'Pangsit Ayam + Bakso Urat',
                'price' => 20000,
                'description' => 'Kombinasi pangsit ayam dan bakso urat',
                'image' => 'menu-images/menu-4.jpg'
            ],
            [
                'name' => 'Mie Ayam Bakso Urat',
                'price' => 20000,
                'description' => 'Mie ayam dengan tambahan bakso urat',
                'image' => 'menu-images/menu-5.jpg'
            ],
            [
                'name' => 'Pangsit Ayam + Bakso Telur',
                'price' => 22000,
                'description' => 'Kombinasi pangsit ayam dan bakso telur',
                'image' => 'menu-images/menu-6.jpg'
            ],
            [
                'name' => 'Mie Ayam Bakso Telur',
                'price' => 21000,
                'description' => 'Mie ayam dengan tambahan bakso telur',
                'image' => 'menu-images/menu-1.jpg'
            ],
            [
                'name' => 'Mieso',
                'price' => 13000,
                'description' => 'Mie soto yang segar dan gurih',
                'image' => 'menu-images/menu-2.jpg'
            ],
            [
                'name' => 'Mie Ayam + Bakso Jumbo',
                'price' => 27000,
                'description' => 'Mie ayam dengan bakso jumbo yang istimewa',
                'image' => 'menu-images/menu-3.jpg'
            ],
            [
                'name' => 'Mieso + Bakso Urat',
                'price' => 20000,
                'description' => 'Mie soto dengan tambahan bakso urat',
                'image' => 'menu-images/menu-4.jpg'
            ],
            [
                'name' => 'Pangsit Bakso Urat',
                'price' => 13000,
                'description' => 'Pangsit dengan bakso urat yang lezat',
                'image' => 'menu-images/menu-5.jpg'
            ],
        ];

        // Menu Minuman
        $menuMinuman = [
            [
                'name' => 'Teh Es',
                'price' => 5000,
                'description' => 'Teh es segar yang menyegarkan',
                'image' => 'menu-images/menu-6.jpg'
            ],
            [
                'name' => 'Jus Melon',
                'price' => 10000,
                'description' => 'Jus melon segar dari buah pilihan',
                'image' => 'menu-images/menu-1.jpg'
            ],
            [
                'name' => 'Teh Panas',
                'price' => 5000,
                'description' => 'Teh panas yang menghangatkan',
                'image' => 'menu-images/menu-2.jpg'
            ],
            [
                'name' => 'Jus Semangka',
                'price' => 10000,
                'description' => 'Jus semangka segar yang menyegarkan',
                'image' => 'menu-images/menu-3.jpg'
            ],
            [
                'name' => 'Es Jeruk',
                'price' => 8000,
                'description' => 'Es jeruk segar dari jeruk pilihan',
                'image' => 'menu-images/menu-4.jpg'
            ],
            [
                'name' => 'Jus Terong Belanda',
                'price' => 10000,
                'description' => 'Jus terong belanda yang menyehatkan',
                'image' => 'menu-images/menu-5.jpg'
            ],
            [
                'name' => 'Jeruk Panas',
                'price' => 7000,
                'description' => 'Jeruk panas yang menghangatkan',
                'image' => 'menu-images/menu-6.jpg'
            ],
            [
                'name' => 'Jus Pokat',
                'price' => 10000,
                'description' => 'Jus alpukat yang creamy dan lezat',
                'image' => 'menu-images/menu-1.jpg'
            ],
            [
                'name' => 'Jus Jeruk',
                'price' => 10000,
                'description' => 'Jus jeruk segar yang menyegarkan',
                'image' => 'menu-images/menu-2.jpg'
            ],
            [
                'name' => 'Jus Buah Naga',
                'price' => 10000,
                'description' => 'Jus buah naga yang kaya antioksidan',
                'image' => 'menu-images/menu-3.jpg'
            ],
            [
                'name' => 'Jus Mangga',
                'price' => 10000,
                'description' => 'Jus mangga manis dari mangga pilihan',
                'image' => 'menu-images/menu-4.jpg'
            ],
            [
                'name' => 'Es Teler',
                'price' => 10000,
                'description' => 'Es teler dengan berbagai macam buah segar',
                'image' => 'menu-images/menu-5.jpg'
            ],
        ];

        // Insert menu makanan dan attach ke kategori makanan
        foreach ($menuMakanan as $menu) {
            MenuItem::create($menu)->categories()->attach($makanan->id);
        }

        // Insert menu minuman dan attach ke kategori minuman
        foreach ($menuMinuman as $menu) {
            MenuItem::create($menu)->categories()->attach($minuman->id);
        }
    }
} 