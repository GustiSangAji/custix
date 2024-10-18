<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['phone','email','nama','user_id', 'ticket_id', 'order_id', 'jumlah_pemesanan', 'total_harga', 'status'];

    // Relasi ke model Ticket
    public function ticket()
    {
        return $this->belongsTo(Tiket::class, 'ticket_id');
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
