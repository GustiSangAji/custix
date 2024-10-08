<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stockins')->insert([
            [
                'uuid' => Str::uuid(),
                'kode_tiket' => 'TICKET001',
                'jumlah' => 100,
                'deskripsi' => 'Tiket konser band rock',
                'datetime' => now()->addDays(7), // Event akan terjadi 7 hari ke depan
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'kode_tiket' => 'TICKET002',
                'jumlah' => 50,
                'deskripsi' => 'Tiket seminar teknologi',
                'datetime' => now()->addDays(14), // Event akan terjadi 14 hari ke depan
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'kode_tiket' => 'TICKET003',
                'jumlah' => 200,
                'deskripsi' => 'Tiket pertandingan sepak bola',
                'datetime' => now()->addDays(30), // Event akan terjadi 30 hari ke depan
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
