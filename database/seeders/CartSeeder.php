<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Gunakan lokal Indonesia untuk data yang lebih relevan

        for ($i = 0; $i < 100; $i++) {
            // Buat bulan acak (1 sampai 12)
            $randomMonth = $faker->numberBetween(1, 12);

            // Tentukan jumlah hari dalam bulan (tergantung bulan dan tahun)
            $randomDay = $faker->numberBetween(1, cal_days_in_month(CAL_GREGORIAN, $randomMonth, now()->year));

            // Buat tanggal acak berdasarkan bulan dan hari
            $randomDate = now()->year . '-' . str_pad($randomMonth, 2, '0', STR_PAD_LEFT) . '-' . str_pad($randomDay, 2, '0', STR_PAD_LEFT) . ' ' . $faker->time();

            // Buat data Cart baru dengan kolom-kolom yang diisi oleh Faker
            Cart::create([
                'order_id' => $faker->unique()->uuid, // UUID unik untuk order_id
                'user_id' => $faker->numberBetween(1, 50), // Asumsi ada 50 user
                'ticket_id' => $faker->numberBetween(1, 50), // Asumsi ada 50 tiket
                'jumlah_pemesanan' => $faker->numberBetween(1, 3), // Jumlah tiket yang dipesan
                'total_harga' => $faker->numberBetween(100000, 1000000), // Harga tiket dalam rentang tertentu
                'status' => $faker->randomElement(['Unpaid', 'Paid',]), // Status tiket
            ]);
        }
    }
}
