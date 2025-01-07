<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Meja Indoor (1-10)
        for ($i = 1; $i <= 10; $i++) {
            Table::create([
                'table_number' => 'IN' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacity' => $i <= 5 ? 2 : 4, // Meja 1-5 kapasitas 2, 6-10 kapasitas 4
                'status' => Table::STATUS_AVAILABLE,
                'location' => Table::LOCATION_INDOOR,
                'description' => 'Meja indoor reguler ' . ($i <= 5 ? 'untuk 2 orang' : 'untuk 4 orang'),
            ]);
        }

        // Meja Outdoor (11-18)
        for ($i = 1; $i <= 8; $i++) {
            Table::create([
                'table_number' => 'OUT' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacity' => 4,
                'status' => Table::STATUS_AVAILABLE,
                'location' => Table::LOCATION_OUTDOOR,
                'description' => 'Meja outdoor dengan pemandangan taman',
            ]);
        }

        // Meja Rooftop (19-24)
        for ($i = 1; $i <= 6; $i++) {
            Table::create([
                'table_number' => 'RT' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacity' => 6,
                'status' => Table::STATUS_AVAILABLE,
                'location' => Table::LOCATION_ROOFTOP,
                'description' => 'Meja rooftop premium dengan pemandangan kota',
            ]);
        }

        // Private Room (25-28)
        $privateRoomCapacities = [8, 10, 12, 15];
        for ($i = 1; $i <= 4; $i++) {
            Table::create([
                'table_number' => 'VIP' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'capacity' => $privateRoomCapacities[$i - 1],
                'status' => Table::STATUS_AVAILABLE,
                'location' => Table::LOCATION_PRIVATE_ROOM,
                'description' => 'Ruang privat eksklusif untuk ' . $privateRoomCapacities[$i - 1] . ' orang',
                'notes' => 'Reservasi minimal H-1',
            ]);
        }

        // Beberapa meja dengan status berbeda untuk testing
        Table::where('table_number', 'IN01')->update(['status' => Table::STATUS_OCCUPIED]);
        Table::where('table_number', 'OUT01')->update(['status' => Table::STATUS_RESERVED]);
        Table::where('table_number', 'RT01')->update(['status' => Table::STATUS_MAINTENANCE]);
    }
} 