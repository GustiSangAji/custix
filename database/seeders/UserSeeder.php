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
            'photo' => '/media/avatar/profz.png',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '08123456789',
            'email_verified_at' => now(),
            'confirmed' => true,
        ])->assignRole('admin');

        User::create([
            'uuid' => Str::uuid(),
            'nama' => 'User',
            'photo' => '/media/avatar/profz.png',
            'email' => 'user@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '081212121212',
            'email_verified_at' => now(),
            'confirmed' => true,
        ])->assignRole('user');

        User::create([
            'uuid' => Str::uuid(),
            'nama' => 'Gilarism',
            'photo' => '/media/avatar/profz.png',
            'email' => 'gilarism@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '08121276212',
            'email_verified_at' => now(),
            'confirmed' => true,
        ])->assignRole('user');

        User::create([
            'uuid' => Str::uuid(),
            'nama' => 'Kairos',
            'photo' => '/media/avatar/profz.png',
            'email' => 'kairo@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '08249696969',
            'email_verified_at' => now(),
            'confirmed' => true,
        ])->assignRole('user');

        User::create([
            'uuid' => Str::uuid(),
            'nama' => 'Gusti Sang Aji',
            'photo' => '/media/avatar/profz.png',
            'email' => 'gusti@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '666',
            'email_verified_at' => now(),
            'confirmed' => true,
            ])->assignRole('user');
            
            // Tambahkan pengguna tambahan menggunakan UserFactory
            for ($i = 0; $i < 50; $i++) {
                User::create([
                    'uuid' => Str::uuid(),
                    'nama' => $faker->name,
                    'photo' => '/avatar/profz.png',
                    'email' => $faker->unique()->safeEmail,
                    'password' => bcrypt('password'), // password default
                    'phone' => $faker->unique()->phoneNumber,
                'email_verified_at' => now(),
                'confirmed' => $faker->boolean,
            ])->assignRole('user');
        }
}
}
