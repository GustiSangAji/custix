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

        // Array nama festival di Indonesia
        $festivalNames = [
            'Synchronize Fest', 'We The Fest', 'Java Jazz Festival', 'Jakarta International BNI Java Jazz Festival',
            'Candi Jazz Festival', 'Djakarta Warehouse Project', 'Sundown Festival', 'Prambanan Jazz Festival',
            'Hellofest', 'Festival Musik Indonesia', 'Pahawang Music Festival', 'Indo Jam Music Festival'
        ];

        // Ambil secara acak nama festival dari array
        $name = $this->faker->randomElement($festivalNames);

        // Array nama kota dan venue
        $cities = [
            'Jakarta', 'Bandung', 'Bali', 'Yogyakarta', 'Surabaya', 
            'Medan', 'Semarang', 'Makassar', 'Malang', 'Batam'
        ];

        $venues = [
            'Gelora Bung Karno', 'Ancol Beach', 'Lapangan D Senayan', 'Trans Studio Bandung',
            'Taman Ismail Marzuki', 'Bali International Convention Centre', 'Kota Tua Jakarta',
            'Prambanan Temple', 'Lapangan Golf Cengkareng', 'Kebun Raya Bogor'
        ];

        // Menghasilkan lokasi secara acak
        $location = $this->faker->randomElement($cities);
        $venue = $this->faker->randomElement($venues);

        return [
            'uuid' => Str::uuid(),
            'kode_tiket' => strtoupper(Str::random(10)),
            'name' => $name, // Nama festival
            'place' => $location . ' - ' . $venue, // Lokasi acara
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
