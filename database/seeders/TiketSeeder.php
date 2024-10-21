<?php

namespace Database\Seeders;

use App\Models\Tiket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TiketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tiket::factory()->count(30)->create();
    }
}