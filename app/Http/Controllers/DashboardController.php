<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pelanggan = Customer::count();
        $pendapatan = Order::sum('total'); // Total pendapatan dari pesanan
        $tiket = Product::where('category', 'Tiket')->count(); // Hitung produk kategori Tiket
        $pesanan = Order::count(); // Total pesanan
        $produkTerjual = Product::sum('quantity_sold'); // Total produk terjual

        return response()->json([
            'pelanggan' => $pelanggan,
            'pendapatan' => $pendapatan,
            'tiket' => $tiket,
            'pesanan' => $pesanan,
            'produkTerjual' => $produkTerjual
        ]);
    }
}