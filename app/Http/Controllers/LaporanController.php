<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Exports\ExportLaporan;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    // Mendapatkan data cart berdasarkan status atau tanpa filter
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Cart::when($request->status, function (Builder $query, string $status) {
                $query->where('status', $status);
            })->with(['user', 'ticket'])->get()
        ]);
    }

    // Mendapatkan daftar laporan dengan pagination dan pencarian
    public function index(Request $request)
    {
        try {
            $per = $request->per ?? 10;
            $page = $request->page ? $request->page - 1 : 0;

            DB::statement('set @no=0+' . $page * $per);
            $data = Cart::when($request->search, function (Builder $query, string $search) {
                $query->whereHas('user', function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                })->orWhereHas('ticket', function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
            })->with(['user', 'ticket'])->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

            return response()->json($data);
        } catch (\Exception $e) {
            // Log error for debugging purposes
            Log::error('Error fetching laporan: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data'], 500);
        }
    }

    // Menampilkan detail laporan berdasarkan ID
    public function show($id)
{
    // Cari data laporan berdasarkan ID
    $laporan = Cart::find($id);

    // Jika laporan tidak ditemukan, kembalikan response 404
    if (!$laporan) {
        return response()->json([
            'success' => false,
            'message' => 'Data laporan tidak ditemukan'
        ], 404);
    }

    // Jika ditemukan, kembalikan data laporan
    return response()->json([
        'success' => true,
        'laporan' => $laporan
    ]);
    

    // Jika laporan tidak ditemukan, kirim respon dengan status error
    return response()->json([
        'success' => false,
        'message' => 'Laporan tidak ditemukan'
    ], 404);
}



    // Export laporan dalam format Excel
    public function export_excel(Request $request)
    {
        $carts = Cart::when($request->search, function (Builder $query, string $search) {
            $query->whereHas('user', function (Builder $query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })->orWhereHas('ticket', function (Builder $query) use ($search) {
                $query->where('name', 'like', "%$search%");
            });
        })->with(['user', 'ticket'])->get();
    
        // Export laporan dalam format Excel menggunakan ExportLaporan
        return Excel::download(new ExportLaporan($carts), 'Laporan.xlsx');
    }

    public function generatePdf()
{
    \Log::info('generatePdf method called'); // Menambahkan log untuk memastikan method ini dipanggil
    
    $laporan = Cart::with(['ticket'])->get();  // Ambil data dari model
    
    // Jika tidak ada data, kembalikan response error
    if ($laporan->isEmpty()) {
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    $pdf = Pdf::loadView('laporan.pdf', ['laporan' => $laporan]);

    return $pdf->download('laporan.pdf');
}




}
