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
         $userId = Auth::id();
         $currentUsers = Cache::get($this->key, []);
     
         Log::info('Current users accessing ticket before adding: ' . json_encode($currentUsers));
     
         if (in_array($userId, $currentUsers)) {
             Log::info('User ' . $userId . ' already in access list.');
             return $next($request);
         }
     
         if (count($currentUsers) >= $this->limit) {
             $this->addToQueue($userId);
             Log::info('User ' . $userId . ' added to waiting room queue.');
             return redirect()->route('waiting-room')->with('message', 'Waiting for access.');
         }
     
         $currentUsers[] = $userId;
     
         // Simpan data currentUsers ke cache selama 10 menit (600 detik)
         Cache::put($this->key, $currentUsers, 600); 
     
         // Log setelah penyimpanan ke cache
         $updatedUsers = Cache::get($this->key, []);
         Log::info('Current users accessing ticket after adding: ' . json_encode($updatedUsers));
     
         Log::info('User ' . $userId . ' granted access. Current users: ' . json_encode($currentUsers));
     
         return $next($request);
     }
     
     

    

    /**
     * Tambahkan pengguna ke antrian jika slot penuh
     */
    protected function addToQueue($userId)
    {
        $queue = Cache::get($this->queueKey, []); // Ambil antrian dari cache
        if (!in_array($userId, $queue)) { // Cek apakah pengguna sudah ada dalam antrian
            $queue[] = $userId; // Tambahkan pengguna ke antrian
            Cache::put($this->queueKey, $queue); // Simpan kembali antrian ke cache
        }
    }

    /**
     * Menghapus pengguna dari antrian dan memberikan akses ke pengguna berikutnya
     */
    public function terminate($request, $response)
    {
        $currentUsers = Cache::get($this->key, []);
    
        // Hapus pengguna yang keluar dari halaman
        $userId = Auth::id();
        $currentUsers = array_diff($currentUsers, [$userId]);
        Cache::put($this->key, $currentUsers);
    
        // Periksa dan izinkan pengguna berikutnya dari antrian
        $queue = Cache::get($this->queueKey, []);
        if (count($queue) > 0) {
            $nextUser = array_shift($queue);
            Cache::put($this->queueKey, $queue);
            $currentUsers[] = $nextUser;
            Cache::put($this->key, $currentUsers);
        }
    }
}
