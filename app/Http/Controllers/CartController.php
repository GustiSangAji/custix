<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use App\Models\Cart; // Model keranjang (buat model jika belum ada)
use App\Models\Tiket; // Model tiket
use Midtrans\Config;
use Midtrans\Notification;
use App\Models\User;
use App\Models\TicketDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        do {
            $orderId = $ticket->kode_tiket . now()->format('YmdHis') . auth()->id(); // Tambahkan ID pengguna
        } while (Cart::where('order_id', $orderId)->exists());

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
        $transactionStatus = $notification['transaction_status'];
        $orderId = $notification['order_id'];

        // Cari pesanan berdasarkan order_id
        $order = Cart::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Mengambil QR code yang disimpan dalam tabel cart
        $qrCodeData = json_decode($order->qr_code, true);

        // Pastikan format QR code mengandung 'tickets'
        if (!isset($qrCodeData['tickets']) || !is_array($qrCodeData['tickets'])) {
            return response()->json(['message' => 'Invalid QR code format'], 400);
        }

        // Ambil tiket terkait pesanan
        $ticket = Tiket::find($order->ticket_id);

        // Gunakan transaksi database untuk memastikan atomicity
        DB::beginTransaction();

        try {
            // Perbarui status berdasarkan status transaksi dari Midtrans
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                // Kurangi stok tiket jika status adalah 'capture' atau 'settlement'
                if ($ticket && $ticket->quantity >= $order->jumlah_pemesanan) {
                    // Mengunci tiket selama transaksi
                    $ticket = Tiket::where('id', $ticket->id)->lockForUpdate()->first();

                    if ($ticket->quantity < $order->jumlah_pemesanan) {
                        // Batalkan transaksi jika stok tidak mencukupi
                        DB::rollBack();
                        return response()->json(['message' => 'Not enough tickets available'], 400);
                    }

                    $ticket->quantity -= $order->jumlah_pemesanan;
                    $ticket->save(); // Simpan perubahan stok tiket
                } else {
                    // Batalkan transaksi jika tiket tidak ditemukan
                    DB::rollBack();
                    return response()->json(['message' => 'Ticket not found or insufficient quantity'], 404);
                }

                // Ubah status pesanan yang baru menjadi paid
                $order->status = 'Paid';

                // Mengisi tabel ticket_details
                foreach ($qrCodeData['tickets'] as $ticketInfo) {
                    // Buat salinan dari data QR code untuk masing-masing tiket
                    $individualQrCodeData = $qrCodeData;
                    // Set hanya satu ticketNumber per entry
                    $individualQrCodeData['tickets'] = [$ticketInfo]; // Hanya satu tiket per QR

                    TicketDetail::create([
                        'cart_id' => $order->id,
                        'ticket_number' => $ticketInfo['ticketNumber'],
                        'status' => 'Unused',
                        'qr_code' => json_encode($individualQrCodeData), // Simpan QR untuk setiap tiket unik
                    ]);
                }
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'cancel' || $transactionStatus == 'failure' || $transactionStatus == 'pending') {
                // Biarkan status pesanan sebelumnya tidak berubah
            } elseif ($transactionStatus == 'expire') {
                $order->status = 'Expired';
            }

            // Simpan perubahan pada status pesanan
            $order->save();

            // Commit transaksi jika semua berjalan lancar
            DB::commit();
            return response()->json(['message' => 'Transaction processed', 'status' => $order->status]);

        } catch (\Exception $e) {
            // Rollback transaksi jika ada kesalahan
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function afterpayment(Request $request)
    {
        $userId = Auth::id();

        // Hapus akses pengguna dari ticket_access setelah pembayaran selesai
        DB::table('ticket_access')->where('user_id', $userId)->delete();

        // Hapus pengguna dari antrian jika ada
        DB::table('ticket_queue')->where('user_id', $userId)->delete();


        // Berikan akses kepada pengguna berikutnya dalam antrian
        $nextInQueue = DB::table('ticket_queue')->orderBy('created_at', 'asc')->first();

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

        return response()->json(['message' => 'Payment successful and access released.']);
    }

    public function removeAccess(Request $request)
    {
        $userId = $request->input('user_id');

        // Hapus pengguna dari ticket_access
        DB::table('ticket_access')->where('user_id', $userId)->delete();

        return response()->json(['message' => 'Akses pengguna berhasil dihapus']);
    }

    public function getUserOrders()
    {
        $orders = Cart::where('user_id', auth()->id())
                    ->where('status', 'Paid')
                    ->with(['ticket', 'ticketDetails' => function ($query) {
                        $query->select('ticket_number', 'status', 'cart_id');
                    }])
                    ->get();
    
        return response()->json($orders);
    }
    


    public function getOrderById($id)
{
    $order = Cart::where('id', $id)
                ->where('user_id', auth()->id()) 
                ->where('status', 'Paid')    
                ->with(['ticket', 'ticketDetails' => function ($query) {
                    $query->select('ticket_number', 'status', 'cart_id');
                }])
                ->first();

    if (!$order) {
        return response()->json(['message' => 'Order not found or not paid'], 404);
    }

    // Tambahkan status tiket ke dalam order jika ada
    $order->ticketDetails->map(function($ticketDetail) use ($order) {
        $order->ticket->status = $ticketDetail->status;
    });

    return response()->json($order);
}


    public function saveQrCode(Request $request)
    
    {
        $request->validate([
            'order_id' => 'required|exists:carts,order_id',
            'qr_codes' => 'required|json', // Pastikan qr_codes dalam format JSON yang valid
        ]);

        $cart = Cart::where('order_id', $request->order_id)->first();

        if ($cart) {
            $cart->qr_code = $request->qr_codes; // Simpan sebagai JSON objek
            $cart->save();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'order_id' => $request->order_id,
                    'qr_codes' => json_decode($request->qr_codes), // Kembalikan JSON objek
                    'message' => 'QR code berhasil disimpan'
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Order tidak ditemukan'
        ], 404);
    }

    


    public function verifyTicket(Request $request)
    {
        // Validasi untuk memastikan order_id dan ticket_number disediakan
        $request->validate([
            'order_id' => 'required|exists:carts,order_id',
            'ticket_number' => 'required|string', // Nomor tiket sebagai string untuk keunikan
        ]);

        // Ambil detail tiket berdasarkan ticket_number dan order_id
        $ticket = TicketDetail::whereHas('cart', function ($query) use ($request) {
            $query->where('order_id', $request->order_id);
        })->where('ticket_number', $request->ticket_number)
        ->first();

        // Jika tiket tidak ditemukan
        if (!$ticket) {
            return response()->json(['message' => 'Tiket tidak ditemukan'], 404);
        }

        // Cek status tiket
        if ($ticket->status === 'Used') {
            return response()->json(['message' => 'Tiket sudah digunakan'], 400);
    
        }

        // Update status tiket sebagai telah digunakan
        $ticket->status = 'Used';
        $ticket->save();

        return response()->json(['message' => 'Tiket valid dan berhasil diverifikasi'], 200);
    }


    public function getOrderDetail($id)
    {
        // Mengambil order berdasarkan ID dan memuat relasi pengguna dan tiket
        $order = Cart::with(['ticket', 'user'])->where('id', $id)->first();
    
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    
        // Ambil hanya data yang diperlukan untuk halaman pembayaran
        return response()->json([
            'order' => [
                'id' => $order->id,
                'order_id' => $order->order_id,
                'total_harga' => $order->total_harga,
                'jumlah_pemesanan'=> $order->jumlah_pemesanan,
                'created_at' => $order->created_at,
            ],
            'ticket' => [
                'kode_tiket' => $order->ticket->kode_tiket,
                'name' => $order->ticket->name,
                'place' => $order->ticket->place, 
                'datetime' => $order->ticket->datetime, 
                'description' => $order->ticket->description, 
                'image' => $order->ticket->image,
            ],
            'user' => [
                'id' => $order->user->id,
                'nama' => $order->user->nama,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
            ],
        ]);
    }

    

}

