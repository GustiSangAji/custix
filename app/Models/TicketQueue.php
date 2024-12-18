<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketQueue extends Model
{
    use HasFactory;
    protected $table = 'ticket_queue';
    protected $fillable = ['user_id', 'ticket_id', 'position'];
}
