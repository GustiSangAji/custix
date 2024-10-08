<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menambahkan data contoh ke tabel orders
        DB::table('orders')->insert([
            [
                'number' => 'ORD001',
                'jumlah' => 2,
                'total_price' => 200.00,
                'payment_status' => '2', // sudah dibayar
                'snap_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'number' => 'ORD002',
                'jumlah' => 1,
                'total_price' => 100.00,
                'payment_status' => '1', // menunggu pembayaran
                'snap_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'number' => 'ORD003',
                'jumlah' => 3,
                'total_price' => 300.00,
                'payment_status' => '3', // kadaluarsa
                'snap_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
