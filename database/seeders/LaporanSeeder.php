<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laporan;

class LaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat beberapa data laporan contoh
        Laporan::create([
            'email' => 'user1@example.com',
            'nama_tiket' => 'Konser Musik A',
            'jumlah' => 2,
            'harga' => 200000,
            'tanggal_pembelian' => now()->subDays(1), // 1 hari yang lalu
            'status' => 'paid',
        ]);

        Laporan::create([
            'email' => 'user2@example.com',
            'nama_tiket' => 'Teater B',
            'jumlah' => 1,
            'harga' => 150000,
            'tanggal_pembelian' => now()->subDays(2), // 2 hari yang lalu
            'status' => 'unpaid',
        ]);

        Laporan::create([
            'email' => 'user3@example.com',
            'nama_tiket' => 'Festival C',
            'jumlah' => 3,
            'harga' => 300000,
            'tanggal_pembelian' => now()->subDays(3), // 3 hari yang lalu
            'status' => 'paid',
        ]);

        // Tambahkan lebih banyak data contoh sesuai kebutuhan
    }
}
