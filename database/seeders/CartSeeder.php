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
        $faker = Faker::create('id_ID'); // Lokal Indonesia

        for ($i = 0; $i < 100; $i++) {
            Cart::create([
                'order_id' => $faker->unique()->uuid, // UUID untuk order_id
                'user_id' => $faker->numberBetween(1, 50), // Asumsi ada 50 user
                'ticket_id' => $faker->numberBetween(1, 50), // Asumsi ada 50 tiket
                'jumlah_pemesanan' => $faker->numberBetween(1, 3), // Jumlah tiket yang dipesan
                'total_harga' => $faker->numberBetween(100000, 1000000), // Harga tiket dalam rentang tertentu
                'status' => $faker->randomElement(['Unpaid', 'Paid', 'Used']), // Status tiket
            ]);
        }
    }
}
