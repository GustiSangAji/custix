<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 6);
        $tickets = Tiket::paginate($perPage, [
            'id', 'kode_tiket', 'name', 'place', 'datetime', 'quantity', 'price', 'description', 'banner', 'image', 'status'
        ]);
    
        return response()->json($tickets);
    }

    public function limited()
    {
        // Mengambil hanya dua tiket untuk halaman utama, termasuk yang tidak tersedia
        $tickets = Tiket::limit(2)->get([
            'id', 'name', 'kode_tiket', 'place', 'datetime', 'quantity', 'price', 'image', 'status'
        ]);
        
        return response()->json($tickets);
    }

    public function show($id)
    {
        // Mengambil detail tiket berdasarkan ID
        $ticket = Tiket::findOrFail($id);
        return response()->json($ticket);
    }

    public function showByName($name)
    {
        // Ubah dash menjadi spasi jika menggunakan spasi di database
    $ticketName = str_replace('-', ' ', $name);
    $ticket = Tiket::where('name', $ticketName)->first();
    
    if (!$ticket) {
        return response()->json(['message' => 'Tiket tidak ditemukan'], 404);
    }
    
    return response()->json($ticket);
    }

}

