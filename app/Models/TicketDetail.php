<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    use HasFactory;

    protected $table = 'ticket_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cart_id',
        'ticket_number',
        'status',
        'qr_code',
    ];

    /**
     * Relasi ke model Cart
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
