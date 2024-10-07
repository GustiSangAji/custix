<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokIn extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari konvensi
    protected $table = 'stok_in';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'tiket_id',
        'description',
        'added_at',
        'amount',
    ];

    // Definisikan relasi dengan model Tiket
    public function stokin()
    {
        return $this->belongsTo(stokin::class, 'tiket_id');
    }
}
