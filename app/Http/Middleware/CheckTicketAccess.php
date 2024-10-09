<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tiket; // Menggunakan model Tiket

class CheckTicketAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Ambil ID tiket dari parameter route
        $ticketId = $request->route('id');

        // Temukan tiket berdasarkan ID
        $ticket = Tiket::find($ticketId);

        if (!$ticket) {
            return response()->json(['message' => 'Tiket tidak ditemukan.'], 404);
        }

        // Tentukan batas akses (misalnya, jumlah pengguna yang diizinkan mengakses)
        $limit = 1000;

        // Dapatkan jumlah pengguna yang saat ini sedang mengakses tiket ini
        // Ini bisa menggunakan cache, database, atau mekanisme lain sesuai kebutuhan
        $currentAccessCount = $this->getCurrentAccessCount($ticketId); // Anda perlu mengimplementasikan fungsi ini

        // Cek apakah tiket sudah melebihi batas akses
        if ($currentAccessCount >= $limit) {
            // Jika sudah melebihi limit, arahkan pengguna ke waiting room
            return redirect('/waiting-room');
        }

        // Jika masih bisa diakses, lanjutkan request
        return $next($request);
    }

    /**
     * Fungsi untuk mendapatkan jumlah pengguna yang saat ini mengakses tiket ini.
     * 
     * @param int $ticketId
     * @return int
     */
    protected function getCurrentAccessCount($ticketId)
    {
        // Implementasikan logika untuk menghitung pengguna yang mengakses tiket
        // Misalnya, menggunakan cache atau database untuk menyimpan akses pengguna
        // Contoh sederhana (gunakan cache atau database yang sesuai):
        
        // Menggunakan cache untuk menyimpan jumlah akses
        $key = 'ticket_access_count_' . $ticketId;
        $currentCount = cache()->get($key, 0);

        // Anda mungkin ingin menambahkan logika untuk memperbarui jumlah ini saat pengguna mulai mengakses
        // Misalnya, ketika pengguna berhasil mengakses endpoint ini, tambahkan 1 ke currentCount

        return $currentCount;
    }
}
