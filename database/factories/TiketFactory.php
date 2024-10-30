<?php

namespace Database\Factories;

use App\Models\Tiket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TiketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tiket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Ganti dengan path ke folder gambar asli Anda
        $imagePath = storage_path('app/public/images');
        $bannerPath = storage_path('app/public/banners');

        // Ambil semua file gambar dari folder
        $images = array_diff(scandir($imagePath), ['..', '.']);
        $banners = array_diff(scandir($bannerPath), ['..', '.']);

        // Pilih gambar secara acak
        $image = 'images/' . $this->faker->randomElement($images);
        $banner = 'banners/' . $this->faker->randomElement($banners);

        // Menghasilkan nama event dan menghapus titik (.)
        $name = $this->faker->sentence(3);
        $name = str_replace('.', '', $name); // Menghapus titik

        return [
            'uuid' => Str::uuid(),
            'kode_tiket' => strtoupper(Str::random(10)),
            'name' => $name, // Nama event tanpa titik
            'place' => $this->faker->city, // Lokasi event
            'datetime' => $this->faker->dateTimeBetween('now', '+1 year'), // Tanggal event
            'status' => $this->faker->randomElement(['available']), // Status tiket
            'quantity' => $this->faker->numberBetween(50, 500), // Stok tiket
            'price' => $this->faker->randomFloat(2, 10, 500), // Harga tiket
            'image' => $image, // Path gambar tiket
            'description' => $this->faker->paragraph, // Deskripsi tiket
            'banner' => $banner, // Path banner tiket
            'expiry_date' => $this->faker->dateTimeBetween('+1 week', '+2 years'), // Masa berlaku tiket
        ];
    }

}
