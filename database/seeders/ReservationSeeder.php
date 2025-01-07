<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa user dengan role customer
        $customers = User::where('role', 'customer')->take(3)->get();

        // Ambil meja yang tersedia
        $tables = Table::where('status', 'available')->take(3)->get();

        // Reservasi 1: Untuk hari ini
        Reservation::create([
            'user_id' => $customers[0]->id,
            'table_id' => $tables[0]->id,
            'reservation_date' => Carbon::today(),
            'start_time' => '18:00',
            'end_time' => '20:00',
            'guest_count' => 4,
            'notes' => 'Tolong siapkan lilin untuk meja',
            'status' => 'approved',
            'reservation_code' => 'RES' . strtoupper(Str::random(8))
        ]);

        // Reservasi 2: Untuk besok (meja yang sama dengan reservasi 1)
        Reservation::create([
            'user_id' => $customers[1]->id,
            'table_id' => $tables[0]->id, // Meja yang sama
            'reservation_date' => Carbon::tomorrow(),
            'start_time' => '19:00',
            'end_time' => '21:00',
            'guest_count' => 2,
            'notes' => 'Perayaan anniversary',
            'status' => 'pending',
            'reservation_code' => 'RES' . strtoupper(Str::random(8))
        ]);

        // Reservasi 3: Untuk hari ini tapi beda jam (meja yang sama dengan reservasi 1)
        Reservation::create([
            'user_id' => $customers[2]->id,
            'table_id' => $tables[0]->id, // Meja yang sama
            'reservation_date' => Carbon::today(),
            'start_time' => '12:00',
            'end_time' => '14:00',
            'guest_count' => 3,
            'notes' => 'Makan siang keluarga',
            'status' => 'completed',
            'reservation_code' => 'RES' . strtoupper(Str::random(8))
        ]);

        // Reservasi 4: Untuk minggu depan (meja berbeda)
        Reservation::create([
            'user_id' => $customers[0]->id, // Customer pertama memesan lagi
            'table_id' => $tables[1]->id, // Meja berbeda
            'reservation_date' => Carbon::today()->addWeek(),
            'start_time' => '18:30',
            'end_time' => '20:30',
            'guest_count' => 6,
            'notes' => 'Ulang tahun anak',
            'status' => 'rejected',
            'rejection_reason' => 'Maaf, pada tanggal tersebut restoran akan tutup untuk maintenance',
            'reservation_code' => 'RES' . strtoupper(Str::random(8))
        ]);
    }
} 