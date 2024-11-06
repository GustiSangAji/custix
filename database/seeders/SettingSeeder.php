<?php

namespace Database\Seeders;

use App\Models\Setting;
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

        // Folder path untuk gambar carousel
        $carouselPath = public_path('storage/carousel');
        $carousels = array_diff(scandir($carouselPath), ['..', '.']);

        // Pastikan minimal ada tiga gambar untuk carousel
        if (count($carousels) >= 3) {
            $carousel1 = 'storage/carousel/' . $carousels[array_rand($carousels)];
            $carousel2 = 'storage/carousel/' . $carousels[array_rand($carousels)];
            $carousel3 = 'storage/carousel/' . $carousels[array_rand($carousels)];
        } else {
            $carousel1 = $carousel2 = $carousel3 = null;
        }

        Setting::create([
            'app' => 'CusTix',
            'description' => 'Beli Tiketmu Disini',
            'logo' => '/media/misc/aminkan.png',
            'carousel1' => $carousel1,
            'carousel2' => $carousel2,
            'carousel3' => $carousel3,
        ]);
    }
}