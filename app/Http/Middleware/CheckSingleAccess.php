<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckSingleAccess
{
    protected $key = 'ticket_access';  // Kunci untuk akses tiket
    protected $queueKey = 'ticket_queue'; // Kunci untuk antrian tiket
    protected $limit = 2; // Limit jumlah pengguna yang bisa mengakses (ubah dari 1 ke 2)

    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        // Cek apakah pengguna terautentikasi
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $currentUsers = Cache::get($this->key, []);
        $userId = Auth::id();
    
        // Jika sudah ada pengguna yang mengakses, tambahkan pengguna ke waiting room
        if (count($currentUsers) > 0) {
            Log::info("User {$userId} added to waiting room.");
            $queue = Cache::get($this->queueKey, []);
            if (!in_array($userId, $queue)) {
                $queue[] = $userId;
                Cache::put($this->queueKey, $queue, 600);
            }
            
            return response()->json(['message' => 'Please wait, another user is accessing the ticket.'], 429);
        }
    
        // Jika tidak ada pengguna, izinkan akses
        Log::info("User {$userId} granted access.");
        $currentUsers[] = $userId;
        Cache::put($this->key, $currentUsers, 600);
    
        return $next($request);
    }
    
    public function terminate($request, $response)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            Log::info("Terminating session for user: " . $userId);
            
            $currentUsers = Cache::get($this->key, []);
            $updatedUsers = array_diff($currentUsers, [$userId]); // Hapus user dari daftar pengguna
            Cache::put($this->key, $updatedUsers, 600);
            Log::info("Current users after termination: " . json_encode($updatedUsers));

            // Periksa antrian untuk pengguna berikutnya
            $queue = Cache::get($this->queueKey, []);
            Log::info("Queue status before processing: " . json_encode($queue));

            if (count($queue) > 0) {
                // Ambil pengguna berikutnya dari antrian
                $nextUser = array_shift($queue); // Mengambil user teratas dari antrian
                Log::info("Next user from queue: " . $nextUser);

                // Simpan kembali antrian yang diperbarui
                Cache::put($this->queueKey, $queue, 600);
                Log::info("Updated queue after shift: " . json_encode($queue));

                // Tambahkan pengguna yang diizinkan ke daftar pengguna aktif
                $updatedUsers[] = $nextUser;
                Cache::put($this->key, $updatedUsers, 600);
                Log::info("User {$nextUser} granted access from queue.");
            }
        } else {
            Log::error("User is not authenticated, skipping termination process.");
        }
    }
}
