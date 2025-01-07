<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Pekanbaru'
        ]);

        // Customer users
        User::insert([
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081234567891',
                'address' => 'Jl. Pelanggan No. 1, Pekanbaru'
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081234567892',
                'address' => 'Jl. Pelanggan No. 2, Pekanbaru'
            ],
            [
                'name' => 'Ahmad Hidayat',
                'email' => 'ahmad@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081234567893',
                'address' => 'Jl. Pelanggan No. 3, Pekanbaru'
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081234567894',
                'address' => 'Jl. Pelanggan No. 4, Pekanbaru'
            ],
        ]);
    }
}
