<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        User::create([
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '08123456789',
            'email_verified_at' => now(),
            'confirmed' => true,
        ])->assignRole('admin');

        User::create([
            'nama' => 'User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '081212121212',
            'email_verified_at' => now(),
            'confirmed' => true,
        ])->assignRole('user');
    }
}
