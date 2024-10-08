<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menambahkan data contoh ke tabel orders
        DB::table('stockins')->insert([
            [
                'kode_tiket' => 'JKT-02',
                'jumlah' => 2,
                'deskripsi' => 'contoh saja',
                'datetime' => '2024-10-01 19:00:00'
            ],
            [
                'kode_tiket' => 'JKT-01',
                'jumlah' => 2,
                'deskripsi' => 'contoh saja',
                'datetime' => '2024-10-01 19:00:00'
            ],[
                'kode_tiket' => 'JKT-03',
                'jumlah' => 2,
                'deskripsi' => 'contoh saja',
                'datetime' => '2024-10-01 19:00:00'
            ],
        ]);
    }
}
