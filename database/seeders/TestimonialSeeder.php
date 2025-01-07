<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua order yang sudah completed
        $orders = Order::where('status', 'completed')
            ->whereDoesntHave('testimonial')
            ->inRandomOrder()
            ->take(5) // Ambil 5 order secara random
            ->get();

        $testimonialContents = [
            [
                'title' => 'Makanan Lezat dan Pelayanan Ramah',
                'content' => 'Saya sangat puas dengan makanan yang disajikan. Rasanya autentik dan porsinya pas. Pelayanan juga sangat ramah dan cepat.',
                'rating' => 5
            ],
            [
                'title' => 'Menu Variatif dan Harga Terjangkau',
                'content' => 'Menu yang ditawarkan sangat beragam dan harganya sangat worth it. Tempatnya juga nyaman untuk makan bersama keluarga.',
                'rating' => 4
            ],
            [
                'title' => 'Pengalaman Makan yang Menyenangkan',
                'content' => 'Suasana restoran sangat nyaman dan bersih. Makanannya enak dan penyajiannya menarik. Pasti akan kembali lagi.',
                'rating' => 5
            ],
            [
                'title' => 'Rekomendasi untuk Pecinta Kuliner',
                'content' => 'Tempat ini wajib dikunjungi bagi pecinta kuliner. Cita rasa yang konsisten dan pelayanan yang memuaskan.',
                'rating' => 4
            ],
            [
                'title' => 'Tempat Favorit untuk Makan',
                'content' => 'Sudah berkali-kali makan di sini dan tidak pernah mengecewakan. Staff ramah dan makanan selalu enak.',
                'rating' => 5
            ]
        ];

        foreach ($orders as $index => $order) {
            $testimonial = $testimonialContents[$index];
            
            Testimonial::create([
                'order_id' => $order->id,
                'title' => $testimonial['title'],
                'content' => $testimonial['content'],
                'rating' => $testimonial['rating'],
                'is_published' => true,
                'is_featured' => $index < 3 // 3 testimonial pertama akan featured
            ]);
        }
    }
} 