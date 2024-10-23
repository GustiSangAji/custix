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
use Illuminate\Support\Str;

class TicketWaitingRoomController extends Controller
{
    protected $limit = 1; // Limit akses ke halaman pembayaran (2 pengguna)

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

    public function grantAccess(Request $request)
    {
        $userId = $request->input('user_id');

        // Masukkan logika untuk memberikan akses di ticket_access
        $access = DB::table('ticket_access')->insert([
            'user_id' => $userId,
            'access_granted_at' => now(),
        ]);

        // Jika akses berhasil diberikan, hapus dari ticket_queue
        if ($access) {
            $this->clearQueue(new Request(['user_id' => $userId])); // Menghapus dari queue
            return response()->json(['message' => 'Akses berhasil diberikan dan antrian dihapus'], 200);
        } else {
            return response()->json(['message' => 'Gagal memberikan akses'], 500);
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

    public function clearQueue(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer', // Ubah ke 'integer' jika menggunakan unsignedBigInteger
        ]);

        TicketQueue::where('user_id', $request->user_id)->delete();

        return response()->json(['message' => 'User removed from queue successfully.']);
    }



}