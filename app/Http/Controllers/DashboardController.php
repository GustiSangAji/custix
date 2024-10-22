<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total pengguna
        $totalPelanggan = User::count();

        // Menghitung total pendapatan dari tabel 'carts' (asumsi status 'Paid' adalah 'dibayar')
        $totalPendapatan = Cart::whereIn('status', ['Paid', 'Used'])->sum('total_harga');

        // Menghitung total quantity tiket yang tersedia
        $totalTiket = Tiket::where('status', 'available')->sum('quantity');

        // Menghitung pendapatan bulanan
        $pendapatanBulanan = Cart::select(DB::raw('MONTH(created_at) as bulan, SUM(total_harga) as total_pendapatan'))
        ->whereIn('status', ['Paid', 'Used']) // Filter untuk cart dengan status 'Paid' atau 'Used'
        ->groupBy(DB::raw('MONTH(created_at)')) // Kelompokkan berdasarkan bulan
        ->orderBy(DB::raw('MONTH(created_at)')) // Urutkan berdasarkan bulan
        ->get();

        // Array untuk memetakan angka bulan menjadi nama bulan
        $namaBulan = [
            1 => 'Jan', 
            2 => 'Feb', 
            3 => 'Mar', 
            4 => 'Apr', 
            5 => 'May', 
            6 => 'Jun', 
            7 => 'Jul', 
            8 => 'Aug', 
            9 => 'Sep', 
            10 => 'Oct', 
            11 => 'Nov', 
            12 => 'Dec'
        ];

        // Mengubah hasil query ke dalam format array dengan bulan dalam bentuk nama
        $pendapatanPerBulan = $pendapatanBulanan->map(function ($item) use ($namaBulan) {
            return [
                'bulan' => $namaBulan[$item->bulan], // Mengubah angka bulan menjadi nama bulan
                'total_pendapatan' => $item->total_pendapatan,
            ];
        });

        // Mengembalikan data dalam format JSON
        return response()->json([
            'pelanggan' => $totalPelanggan, 
            'pendapatan' => $totalPendapatan, 
            'pendapatan_bulanan' => $pendapatanPerBulan, // Pendapatan bulanan dengan nama bulan
            'tiket' => $totalTiket, 
        ]);
    }
}
