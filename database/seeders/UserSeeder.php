<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete(); // Menghapus semua data sebelumnya di tabel users

        $faker = Faker::create('id_ID'); // Menggunakan faker dengan lokal Indonesia

        // User admin dan user tetap
        User::create([
            'uuid' => Str::uuid(),
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '08123456789',
            'email_verified_at' => now(),
            'confirmed' => true,
        ])->assignRole('admin');

        User::create([
            'uuid' => Str::uuid(),
            'nama' => 'User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '081212121212',
            'email_verified_at' => now(),
            'confirmed' => true,
        ])->assignRole('user');

        // Faker untuk menambahkan 50 user acak
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'uuid' => Str::uuid(),
                'nama' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password'), // password default
                'phone' => $faker->unique()->phoneNumber,
                'email_verified_at' => now(),
                'confirmed' => $faker->boolean,
            ])->assignRole('user');
        }
    }
}
