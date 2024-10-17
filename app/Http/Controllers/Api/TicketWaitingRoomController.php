<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\TicketAccess;
use App\Models\TicketQueue;

class TicketWaitingRoomController extends Controller
{
    protected $limit = 2; // Limit akses ke halaman pembayaran (2 pengguna)

    public function status()
    {
        $userId = Auth::id(); // Mendapatkan ID pengguna yang sedang login

        // Cek jumlah pengguna yang sedang mengakses tiket (hanya yang active)
        $currentAccessCount = DB::table('ticket_access')
            ->where('active', true)
            ->count(); // Menghitung berapa pengguna yang aktif membeli tiket

        // Jika slot masih tersedia (kurang dari limit)
        if ($currentAccessCount < $this->limit) {
            DB::table('ticket_access')->insert([
                'user_id' => $userId,
                'active' => true,
                'created_at' => now(),
            ]);
            return response()->json([
                'accessGranted' => true,
                'queuePosition' => null
            ]);
        }

        // Jika tidak ada slot yang tersedia, tambahkan ke antrian
        $queuePosition = DB::table('ticket_queue')
            ->where('user_id', $userId)
            ->value('position');
        
        // Jika pengguna belum dalam antrian, tambahkan ke antrian
        if (!$queuePosition) {
            $maxPosition = DB::table('ticket_queue')->max('position');
            DB::table('ticket_queue')->insert([
                'user_id' => $userId,
                'position' => $maxPosition + 1,
                'created_at' => now(),
            ]);
            $queuePosition = $maxPosition + 1;
        }

        // Kirimkan informasi tentang posisi antrian pengguna
        return response()->json([
            'accessGranted' => false,
            'queuePosition' => $queuePosition
        ]);
    }

    public function grantAccess()
    {
        $user = Auth::user();

        // Cek jumlah pengguna yang aktif
        $currentAccessCount = TicketAccess::where('active', true)->count();

        if ($currentAccessCount < $this->limit) {
            // Grant akses ke user
            TicketAccess::create([
                'user_id' => $user->id,
                'active' => true
            ]);
            Log::info("User {$user->id} granted access.");
            return response()->json(['message' => 'Access granted.']);
        } else {
            // Masukkan ke antrian jika slot penuh
            TicketQueue::create(['user_id' => $user->id]);
            Log::info("User {$user->id} added to queue.");
            return response()->json(['message' => 'Added to queue.']);
        }
    }

    public function terminateAccess()
    {
        $user = Auth::user();

        // Hapus dari akses
        TicketAccess::where('user_id', $user->id)->delete();
        Log::info("Terminating session for user: {$user->id}");

        // Proses antrian
        $nextUser = TicketQueue::orderBy('created_at')->first();
        if ($nextUser) {
            // Pindahkan user dari antrian ke akses
            TicketAccess::create([
                'user_id' => $nextUser->user_id,
                'active' => true
            ]);
            Log::info("User {$nextUser->user_id} granted access from queue.");
            DB::table('ticket_queue')->where('user_id', $nextUser->user_id)->delete(); // Hapus dari antrian
        }

        return response()->json(['message' => 'Access terminated.']);
    }

    public function removeUser($userId)
    {
        // Menghapus pengguna dari tabel ticket_access
        DB::table('ticket_access')->where('user_id', $userId)->delete();

        // Juga hapus dari antrian jika ada
        DB::table('ticket_queue')->where('user_id', $userId)->delete();

        return response()->json(['message' => 'User removed from access and queue.']);
    }
}