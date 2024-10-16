<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WaitingRoomMiddleware
{
    protected $maxUsers = 1; // Jumlah maksimum pengguna yang dapat masuk

    public function handle(Request $request, Closure $next)
    {
        // Cek jumlah pengguna yang sudah masuk
        $currentUsers = cache()->get('current_users', 0);

        if ($currentUsers >= $this->maxUsers) {
            // Jika sudah penuh, redirect ke waiting room
            return redirect('/waiting-room');
        }

        // Tambahkan pengguna yang sedang aktif
        cache()->increment('current_users');

        // Setiap kali pengguna keluar, decrement jumlah pengguna
        $response = $next($request);
        $response->headers->set('X-Current-User-Count', $currentUsers + 1);

        // Menggunakan event untuk mengurangi pengguna saat keluar
        register_shutdown_function(function () {
            cache()->decrement('current_users');
        });

        return $response;
    }

    

}
