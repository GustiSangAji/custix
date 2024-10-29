<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->truncate();

        Setting::create([
            'app' => 'CusTix',
            'description' => 'Beli Tiketmu Disini',
            'logo' => '/media/misc/aminkan.png',
            'bg_auth' => '/media/misc/DmdnYIPmAIvW8d1dLHVkLjAUrS15GeY3bpAHTxxH.jpg',
            'banner' => '/media/misc/DmdnYIPmAIvW8d1dLHVkLjAUrS15GeY3bpAHTxxH.jpg',
            'pemerintah' => 'Pemerintah Provinsi Jawa Timur',
            'dinas' => 'Dinas Lingkungan Hidup',
            'alamat' => '',
            'telepon' => '',
            'email' => '',
        ]);
    }
}
