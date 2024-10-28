<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAccess extends Model
{
    protected $table = 'ticket_access';
    use HasFactory;
    protected $fillable = ['user_id', 'ticket_id', 'active'];
}
