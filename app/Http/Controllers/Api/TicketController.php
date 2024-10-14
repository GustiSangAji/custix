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
        $tickets = Tiket::where('status', 'available')
        ->get(['id','kode_tiket', 'name', 'place', 'datetime', 'quantity', 'price','description','banner', 'image']);
        return response()->json($tickets);
    }

    public function limited()
    {
        // Mengambil hanya dua tiket pertama yang tersedia untuk halaman utama
        $tickets = Tiket::where('status', 'available')
            ->limit(2)
            ->get(['id', 'name', 'kode_tiket', 'place', 'datetime', 'quantity', 'price', 'image']);
        return response()->json($tickets);
    }

    public function show($id)
    {
        // Mengambil detail tiket berdasarkan ID
        $ticket = Tiket::findOrFail($id);
        return response()->json($ticket);
    }
}
