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

        User::create([
            'uuid' => Str::uuid(),
            'nama' => 'Gilarism',
            'email' => 'gilarism@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '08121276212',
            'email_verified_at' => now(),
            'confirmed' => true,
        ])->assignRole('user');

        User::create([
            'uuid' => Str::uuid(),
            'nama' => 'Kairos',
            'email' => 'kairo@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '08249696969',
            'email_verified_at' => now(),
            'confirmed' => true,
        ])->assignRole('user');

        User::create([
            'uuid' => Str::uuid(),
            'nama' => 'Gusti dajjal',
            'email' => 'gusti@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '082666666666',
            'email_verified_at' => now(),
            'confirmed' => true,
        ])->assignRole('user');
    }
}
