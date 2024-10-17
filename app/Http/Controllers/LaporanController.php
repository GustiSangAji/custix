<?php

namespace App\Http\Controllers;

use App\Exports\ExportLaporan;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Cart::when($request->status, function (Builder $query, string $status) {
                $query->where('status', $status);
            })->with(['user', 'ticket'])->get()
        ]);
    }

    public function index(Request $request)
    {
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
    }

    public function export_excel(Request $request)
    {
        $carts = Cart::when($request->search, function (Builder $query, string $search) {
            $query->whereHas('user', function (Builder $query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })->orWhereHas('ticket', function (Builder $query) use ($search) {
                $query->where('name', 'like', "%$search%");
            });
        })->with(['user', 'ticket'])->get();
    
        // Mengirimkan data yang sudah difilter ke dalam class ExportLaporan
        return Excel::download(new ExportLaporan($carts), 'Laporan.xlsx');
    }
}
