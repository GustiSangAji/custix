<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class WaitingRoomMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        $maxAllowedUsers = 1; // Jumlah maksimum pengguna di waiting room
        $waitingRoomKey = 'waiting_room_users';

        // Cek jumlah pengguna saat ini di waiting room
        $currentUsers = Cache::get($waitingRoomKey, 0);

        if ($currentUsers >= $maxAllowedUsers) {
            return response()->json([
                'success' => false,
                'message' => 'Waiting room penuh. Silakan coba lagi nanti.'
            ], 429); // HTTP status code for Too Many Requests
        }

        // Tambahkan pengguna ke waiting room
        Cache::increment($waitingRoomKey);

        $response = $next($request);

        // Keluarkan pengguna dari waiting room setelah request selesai
        Cache::decrement($waitingRoomKey);

        return $response;
    }
}
