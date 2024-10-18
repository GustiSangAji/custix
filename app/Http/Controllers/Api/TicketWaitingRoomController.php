<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\TicketAccess;
use App\Models\TicketQueue;
use App\Models\User;

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
    public function terminateAccess(Request $request)
    {
        $userId = $request->user_id;
    
        // Tambahkan log untuk memastikan request diterima
        Log::info("Menghapus akses untuk user ID: $userId");
    
        // Hapus user dari ticket_access
        $deleted = DB::table('ticket_access')->where('user_id', $userId)->delete();
    
        // Cek apakah penghapusan berhasil
        if ($deleted) {
            Log::info("User ID: $userId berhasil dihapus dari ticket_access.");
        } else {
            Log::error("Gagal menghapus user ID: $userId dari ticket_access.");
        }
    
        return response()->json(['message' => 'Akses dihapus'], 200);
    }
    

    public function removeAccess(Request $request)
    {
        // Mendapatkan user_id dari request
        $userId = $request->input('user_id');
    
        // Hapus pengguna dari ticket_access
        $deleted = DB::table('ticket_access')->where('user_id', $userId)->delete();
    
        if ($deleted) {
            Log::info("User ID: $userId berhasil dihapus dari ticket_access.");
            return response()->json(['message' => 'Akses pengguna berhasil dihapus'], 200);
        } else {
            Log::error("Gagal menghapus user ID: $userId dari ticket_access.");
            return response()->json(['message' => 'Tidak ada akses yang ditemukan untuk pengguna ini'], 404);
        }
    }
    
}