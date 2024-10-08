<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class stockin extends Model
{
    protected $fillable = [
        'uuid',
        'kode_tiket',
        'deskripsi',
        'datetime',
        'jumlah',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($stockin) {
            $stockin->uuid = Str::uuid();
        });
    }
}
