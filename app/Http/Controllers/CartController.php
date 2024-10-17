<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use App\Models\Cart; // Model keranjang (buat model jika belum ada)
use App\Models\Tiket; // Model tiket
use Midtrans\Config;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;
use App\Models\User;
use Illuminate\Support\Str;

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

        // Menghasilkan order_id yang unik
        do {
            $orderId = Str::random(10);
        } while (Cart::where('order_id', $orderId)->exists()); // Cek apakah order_id sudah ada

        // Buat entri baru di keranjang
        $cart = Cart::create([
            'order_id' => $orderId,
            'user_id' => auth()->id(), // Ambil dari user yang sedang login
            'ticket_id' => $request->product['id'],
            'jumlah_pemesanan' => $request->jumlah_pemesanan,
            'total_harga' => $request->product['total_harga'],
            'status' => 'Unpaid' // Set default status
        ]);

        return response()->json(['message' => 'Tiket berhasil ditambahkan ke keranjang', 'cart' => $cart], 201);
    }


    public function show($id)
    {
        $cart = Cart::findOrFail($id);
        return response()->json($cart);
    }

    public function checkout($id)
    {
        $cart = Cart::with('user', 'ticket')->findOrFail($id);

        if (!$cart) {
            return response()->json(['error' => 'Keranjang tidak ditemukan'], 404);
        }

        $user = $cart->user;
        $ticket = $cart->ticket;

        // Set konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false; // ganti ke true jika di production
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Data transaksi
        $transactionDetails = [
            'transaction_details' => [
                'order_id' => $cart->order_id,
                'gross_amount' => (int) $cart->total_harga,
            ],
            'customer_details' => [
                'first_name' => $user->nama,
                'phone' => $user->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $ticket->id,
                    'price' => (int) $ticket->price,
                    'quantity' => $cart->jumlah_pemesanan,
                    'name' => $ticket->name, // Mengambil nama tiket
                ],
            ],
        ];

        // Buat Snap Token
        try {
            $snapToken = \Midtrans\Snap::getSnapToken($transactionDetails);

            return response()->json([
                'id' => $cart->order_id,
                'snap_token' => $snapToken,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function callback(Request $request)
    {  
        $notification = $request->all();

        // Contoh bagaimana menangani notifikasi status pembayaran
        $transactionStatus = $notification['transaction_status'];
        $orderId = $notification['order_id'];

        // Cari pesanan berdasarkan order_id
        $order = Cart::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Perbarui status berdasarkan status transaksi dari Midtrans
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->status = 'Paid';
        } elseif ($transactionStatus == 'pending') {
            $order->status = 'Unpaid';
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $order->status = 'Unpaid';
        }

        $order->save();

        return response()->json(['message' => 'Payment notification handled']);
    }
    
    public function afterpayment(Request $request)
    {
        $userId = Auth::id();
    
        // Logika untuk menghapus pengguna dari ticket_access
        DB::table('ticket_access')->where('user_id', $userId)->delete();
    
        // Hapus pengguna dari antrian jika ada
        DB::table('ticket_queue')->where('user_id', $userId)->delete();
    
        // Berikan akses kepada pengguna berikutnya dalam antrian
        $nextInQueue = DB::table('ticket_queue')->orderBy('position', 'asc')->first();
    
        if ($nextInQueue) {
            // Tambahkan pengguna berikutnya ke dalam ticket_access
            DB::table('ticket_access')->insert([
                'user_id' => $nextInQueue->user_id,
                'active' => true,
                'created_at' => now(),
            ]);
    
            // Hapus pengguna dari antrian
            DB::table('ticket_queue')->where('user_id', $nextInQueue->user_id)->delete();
        }
    
        return response()->json(['message' => 'Payment successful and access released.']);
    }
    

}
