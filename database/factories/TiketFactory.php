<?php

namespace Database\Factories;

use App\Models\Tiket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
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
        // Mengupload gambar palsu ke storage lokal
        $image = UploadedFile::fake()->image('event.jpg', 640, 480)->store('images', 'public');
        $banner = UploadedFile::fake()->image('banner.jpg', 1280, 720)->store('banners', 'public');

        return [
            'uuid' => Str::uuid(),
            'kode_tiket' => strtoupper(Str::random(10)),
            'name' => $this->faker->sentence(3), // Nama event
            'place' => $this->faker->city, // Lokasi event
            'datetime' => $this->faker->dateTimeBetween('now', '+1 year'), // Tanggal event
            'status' => $this->faker->randomElement(['available', 'unavailable']), // Status tiket
            'quantity' => $this->faker->numberBetween(50, 500), // Stok tiket
            'price' => $this->faker->randomFloat(2, 10, 500), // Harga tiket
            'image' => $image, // Path gambar tiket
            'description' => $this->faker->paragraph, // Deskripsi tiket
            'banner' => $banner, // Path banner tiket
            'expiry_date' => $this->faker->dateTimeBetween('+1 week', '+2 years'), // Masa berlaku tiket
        ];
    }
}