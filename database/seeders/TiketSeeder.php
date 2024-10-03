<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tiket;
use Illuminate\Support\Str; // Import Str untuk UUID

class TiketSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Data contoh untuk tiket
        Tiket::create([
            'uuid' => Str::uuid(), // Menambahkan UUID
            'name' => 'Konser Musik A',
            'place' => 'Stadium A',
            'datetime' => '2024-10-01 19:00:00',
            'status' => 'available',
            'quantity' => 150,
            'price' => 6900000,
            'image' => '/storage/tikets/konser-musik-a.jpg',
        ]);

        Tiket::create([
            'uuid' => Str::uuid(), // Menambahkan UUID
            'name' => 'GUYON WATON',
            'place' => 'Stadion Bung Amba',
            'datetime' => '2024-10-01 19:00:00',
            'status' => 'available',
            'quantity' => 150,
            'price' => 100000,
            'image' => '/storage/tikets/konser-musik-a.jpg',
        ]);
        Tiket::create([
            'uuid' => Str::uuid(), // Menambahkan UUID
            'name' => 'Konser Jekate 48',
            'place' => 'Stadion amba tukam',
            'datetime' => '2024-10-01 19:00:00',
            'status' => 'available',
            'quantity' => 150,
            'price' => 6900000,
            'image' => '/storage/tikets/jeketi.jpg',
        ]);

        // Tambahkan data lainnya jika perlu
    }
}
