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
            'id',
            'kode_tiket',
            'name',
            'place',
            'datetime',
            'quantity',
            'price',
            'description',
            'banner',
            'image',
            'status'
        ]);

        return response()->json($tickets);
    }

    public function limited()
    {
        // Mengambil hanya dua tiket untuk halaman utama, termasuk yang tidak tersedia
        $tickets = Tiket::limit(2)->get([
            'id',
            'name',
            'kode_tiket',
            'place',
            'datetime',
            'quantity',
            'price',
            'image',
            'status'
        ]);

        return response()->json($tickets);
    }

    public function show($id)
    {
        // Mengambil detail tiket berdasarkan ID
        $ticket = Tiket::findOrFail($id);
        return response()->json($ticket);
    }

    public function search(Request $request)
    {
        // Mendapatkan input pencarian dari request
        $query = $request->input('query');

        // Mencari tiket berdasarkan nama atau lokasi dengan status 'available'
        $tickets = Tiket::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
        })
            ->where('status', 'available') // Kondisi status available
            ->get(['id', 'name', 'place', 'datetime', 'image']); // Kolom yang diambil

        return response()->json($tickets);
    }

}

