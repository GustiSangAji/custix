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

            return response()->json($carts);
        }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'jumlah_pemesanan' => 'required|integer|min:1',
            'product.id' => 'required|exists:tikets,id', // Validasi ID tiket
            'product.nama_tiket' => 'required|string', // Validasi nama tiket
            'product.total_harga' => 'required|numeric|min:0', // Validasi total harga
            'user_id' => 'required|exists:users,id', // Validasi ID pengguna
        ]);

        // Ambil data tiket dari database
        $ticket = Tiket::find($request->product['id']);
        
        // Cek apakah tiket tersedia
        if (!$ticket || $ticket->quantity < $request->jumlah_pemesanan) {
            return response()->json(['message' => 'Tiket tidak tersedia'], 400);
        }

        // Kurangi stok tiket
        $ticket->quantity -= $request->jumlah_pemesanan;
        $ticket->save();

        // Buat entri baru di keranjang
        $cart = Cart::create([
            'user_id' => $request->user_id, // Ambil dari request
            'ticket_id' => $request->product['id'],
            'jumlah_pemesanan' => $request->jumlah_pemesanan,
            'total_harga' => $request->product['total_harga'], // Ambil dari payload
        ]);

        // Kembalikan respon sukses
        return response()->json(['message' => 'Tiket berhasil ditambahkan ke keranjang', 'cart' => $cart], 201);
    }

    public function show($id)
    {
        $order = Cart::findOrFail($id);
        return response()->json($order);
    }


}

