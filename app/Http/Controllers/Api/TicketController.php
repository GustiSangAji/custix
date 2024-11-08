<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use App\Models\Tanggal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Log;

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

    public function search(Request $request)
    {
        $query = $request->input('query');

        // Filter tiket berdasarkan nama yang sesuai dengan query
        $tickets = Tiket::where('name', 'LIKE', "%{$query}%")
            ->where('status', 'available')
            ->get(['id', 'name', 'place', 'datetime', 'image'])
            ->map(function ($ticket) {
                $ticket->image = Storage::url($ticket->image); // Menambahkan path storage
                return $ticket;
            });

        return response()->json($tickets);
    }

    public function getTicketsByDate(Request $request)
{
    // Validasi parameter tanggal yang diterima
    $validated = $request->validate([
        'date' => 'required|date_format:Y-m-d',
    ]);

    // Mengambil tiket yang dijadwalkan pada tanggal tertentu
    $date = Carbon::parse($request->input('date'))->format('Y-m-d'); // Format tanggal yang diterima
    $tickets = Tiket::whereDate('datetime', $date)->get([ 
        'id', 'kode_tiket', 'name', 'place', 'datetime', 'quantity', 'price', 'description', 'banner', 'image', 'status'
    ]);

    // Jika tidak ada tiket pada tanggal tersebut
    if ($tickets->isEmpty()) {
        return response()->json(['message' => 'Tidak ada tiket untuk tanggal ini'], 404);
    }

    return response()->json(['data' => $tickets]);
}

}