<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        // Mengambil semua tiket yang tersedia
        $tickets = Tiket::where('status', 'available')->get();
        return response()->json($tickets);
    }

    public function show($id)
    {
        // Mengambil detail tiket berdasarkan ID
        $ticket = Tiket::findOrFail($id);
        return response()->json($ticket);
    }
}
