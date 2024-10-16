<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari nama model
    protected $table = 'laporan';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'email',
        'nama_tiket',
        'jumlah',
        'harga',
        'tanggal_pembelian',
        'status',
    ];

    // Jika Anda memiliki relasi, definisikan di sini
    // Contoh relasi dengan model User (jika ada)
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
