<?php

namespace App\Http\Controllers;
use App\Exports\ExportLaporan;
use Maatwebsite\Excel\Facades\Excel; // Tambahkan ini untuk mengimpor Excel
use App\Models\Laporan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Mendapatkan laporan dengan filter status opsional.
     */
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Laporan::when($request->status, function (Builder $query, string $status) {
                $query->where('status', $status);
            })->get()
        ]);
    }

    /**
     * Menampilkan daftar laporan dengan pagination.
     */
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Laporan::when($request->search, function (Builder $query, string $search) {
            $query->where('nama_tiket', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('tanggal_pembelian', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    /**
     * Menyimpan laporan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'nama_tiket' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'tanggal_pembelian' => 'required|date',
            'status' => 'required|string|in:paid,unpaid',
        ]);

        $laporan = Laporan::create($request->all());

        return response()->json([
            'success' => true,
            'laporan' => $laporan
        ], 201);
    }

    /**
     * Menampilkan laporan tertentu berdasarkan ID.
     */
    public function show(Laporan $laporan)
    {
        return response()->json(['success' => true, 'laporan' => $laporan]);
    }

    /**
     * Mengupdate laporan berdasarkan ID.
     */
    public function update(Request $request, Laporan $laporan)
    {
        $request->validate([
            'email' => 'required|email',
            'nama_tiket' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'tanggal_pembelian' => 'required|date',
            'status' => 'required|string|in:paid,unpaid',
        ]);

        $laporan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diupdate',
            'laporan' => $laporan
        ]);
    }

    /**
     * Mengupdate jumlah stok pada laporan tertentu.
     */
    public function updateStok(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:0',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->jumlah = $request->input('jumlah');
        $laporan->save();

        return response()->json(['success' => true, 'message' => 'Stok laporan berhasil diperbarui']);
    }

    /**
     * Menghapus laporan tertentu berdasarkan ID.
     */
    public function destroy(Laporan $laporan)
    {
        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus'
        ]);
    }

    function export_excel(){
        return Excel::download(new ExportLaporan, "Laporan.xlxs");
    }
}
