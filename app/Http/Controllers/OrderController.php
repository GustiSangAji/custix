<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Get all orders (optional if needed).
     */
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders, 200);
    }

    /**
     * Store a new order.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'product_id' => 'required|string',
            'jumlah_pemesanan' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        // Membuat order baru
        $order = new Order();
        $order->order_id = 'ORD-' . strtoupper(uniqid()); // Order ID unik
        $order->product_id = $validatedData['product_id'];
        $order->jumlah_pemesanan = $validatedData['jumlah_pemesanan'];
        $order->total_price = $validatedData['total_price'];
        $order->payment_status = '1'; // Status default: menunggu pembayaran
        $order->save();

        // Mengirim respon dengan order ID
        return response()->json([
            'id' => $order->id,
            'order_id' => $order->order_id,
            'message' => 'Order berhasil dibuat!',
        ], 201);
    }

    /**
     * Show specific order.
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order, 200);
    }

    /**
     * Update specific order.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'payment_status' => 'required|in:1,2,3', // Mengupdate status pembayaran
        ]);

        // Mengambil data order
        $order = Order::findOrFail($id);
        $order->payment_status = $validatedData['payment_status'];
        $order->save();

        return response()->json([
            'message' => 'Status pembayaran berhasil diupdate!',
            'order' => $order,
        ], 200);
    }

    /**
     * Delete specific order (optional if needed).
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'message' => 'Order berhasil dihapus!',
        ], 200);
    }
}
