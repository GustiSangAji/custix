<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tiket extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'place',
        'datetime',
        'status',
        'quantity',
        'price',
        'image',
    ];

    // Menambahkan UUID saat membuat model
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tiket) {
            $tiket->uuid = Str::uuid();
        });
    }
}
