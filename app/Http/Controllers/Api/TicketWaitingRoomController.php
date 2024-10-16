<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class TicketWaitingRoomController extends Controller
{
    protected $key = 'ticket_access';
    protected $queueKey = 'ticket_queue';
    protected $limit = 2; // Ubah dari 1 menjadi 2

    /**
     * Cek status akses tiket atau antrian.
     */
    public function status()
    {
        // Ambil pengguna yang sedang mengakses
        $currentUsers = Cache::get($this->key, []);
        $accessGranted = count($currentUsers) < $this->limit; // Cek jika ada slot

        // Cek apakah user saat ini berada di urutan teratas antrian
        $queue = Cache::get($this->queueKey, []);
        $isInQueue = in_array(Auth::id(), $queue); // Apakah user ada dalam antrian
        $queuePosition = array_search(Auth::id(), $queue); // Posisi user dalam antrian

        return response()->json([
            'accessGranted' => $accessGranted, // Apakah akses sudah diperoleh
            'isInQueue' => $isInQueue, // Apakah user dalam antrian
            'queuePosition' => $queuePosition !== false ? $queuePosition + 1 : null, // Posisi antrian
        ]);
    }

    /**
     * Kosongkan antrian dan akses.
     */
    public function clearQueue()
    {
        Cache::forget($this->key);
        Cache::forget($this->queueKey);

        return response()->json(['message' => 'Access and queue cleared.']);
    }
}
