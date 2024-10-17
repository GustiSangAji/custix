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
    protected $limit = 1; // Limit akses ke halaman pembayaran

    public function status()
{
    $userId = Auth::id(); // Mendapatkan ID pengguna yang sedang login
    
    // Cek pengguna yang sedang mengakses tiket
    $currentAccess = DB::table('ticket_access')
        ->where('active', true)
        ->first(); // Mengambil pengguna yang sedang aktif membeli tiket
    
    // Jika belum ada pengguna yang membeli tiket (slot kosong)
    if (!$currentAccess) {
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

    // Jika pengguna lain sedang membeli tiket, tambahkan ke antrian
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

        // Cek apakah ada slot akses
        $currentUsers = TicketAccess::all();
        if ($currentUsers->count() < $this->limit) {
            // Grant akses ke user
            TicketAccess::create(['user_id' => $user->id]);
            Log::info("User {$user->id} granted access.");
            return response()->json(['message' => 'Access granted.']);
        } else {
            // Masukkan ke antrian
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
            TicketAccess::create(['user_id' => $nextUser->user_id]);
            Log::info("User {$nextUser->user_id} granted access from queue.");
            $nextUser->delete();
        }

        return response()->json(['message' => 'Access terminated.']);
    }
}
