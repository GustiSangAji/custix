<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Stockin extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'kode_tiket',
        'jumlah',
        'deskripsi',
        'datetime',
    ];

    // Automatically generate UUID when creating new stock
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($stockin) {
            $stockin->uuid = Str::uuid();
        });
    }
}
