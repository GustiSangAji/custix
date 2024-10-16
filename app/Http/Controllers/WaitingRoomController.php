<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WaitingRoomController extends Controller
{
    public function status(Request $request)
    {
        // Ambil jumlah pengguna yang sedang aktif
        $currentUsers = cache()->get('current_users', 0);
        
        // Cek apakah pengguna dapat masuk ke penjualan tiket
        $canEnter = $currentUsers < 1; // Menggunakan batasan 100

        return response()->json([
            'entered' => $canEnter,
            'position' => $currentUsers + 1, // Posisi pengguna di antrean
        ]);
    }
}
