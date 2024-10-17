<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket; // Impor model Tiket
use App\Models\User;  // Impor model User
use App\Models\Cart; // Impor model Cart
use Illuminate\Support\Facades\DB; // Impor DB facade jika diperlukan

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total pengguna
        $totalPelanggan = User::count();

        // Menghitung total pendapatan dari tabel 'carts' (asumsi status '2' adalah 'Paid')
        $totalPendapatan = Cart::where('status', 'Paid')->sum('total_harga'); // Pastikan status yang digunakan sesuai

        // Menghitung total quantity tiket yang tersedia
        $totalTiket = Tiket::where('status', 'available')->sum('quantity'); // Menghitung total quantity tiket

        // Mengembalikan data dalam format JSON
        return response()->json([
            'pelanggan' => $totalPelanggan,
            'pendapatan' => $totalPendapatan,
            'tiket' => $totalTiket, // Menggunakan total quantity
        ]);
    }
}
