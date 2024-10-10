<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart; // Model keranjang (buat model jika belum ada)
use App\Models\Tiket; // Model tiket

class CartController extends Controller
{
        public function index()
    {
        // Ambil semua item di keranjang untuk pengguna yang sedang login
        $carts = Cart::where('user_id', auth()->id())->with('ticket')->get();
        \Log::info($carts); // Log untuk melihat hasil query

        return response()->json($carts);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'jumlah_pemesanan' => 'required|integer|min:1',
            'product' => 'required',
        ]);

        // Cek apakah tiket tersedia
        $ticket = Tiket::find($request->product['id']);
        if (!$ticket || $ticket->quantity < $request->jumlah_pemesanan) {
            return response()->json(['message' => 'Tiket tidak tersedia'], 400);
        }

        // Kurangi stok tiket
        $ticket->quantity -= $request->jumlah_pemesanan;
        $ticket->save();

        // Buat entri baru di keranjang
        $cart = Cart::create([
            'user_id' => auth()->id(), // Sesuaikan dengan user yang login
            'ticket_id' => $request->product['id'],
            'jumlah_pemesanan' => $request->jumlah_pemesanan,
            'total_harga' => $request->jumlah_pemesanan * $ticket->price,
        ]);

        return response()->json(['message' => 'Tiket berhasil ditambahkan ke keranjang', 'cart' => $cart], 201);
    }
}

