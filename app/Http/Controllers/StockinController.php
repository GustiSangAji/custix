<?php

namespace App\Http\Controllers;

use App\Models\Stockin;
use App\Http\Requests\StoreStockinRequest;
use App\Http\Requests\UpdateStockinRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockinController extends Controller
{
    /**
     * Get stockins with optional filtering by status.
     */
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Stockin::when($request->status, function (Builder $query, string $status) {
                $query->where('status', $status);
            })->get()
        ]);
    }

    /**
     * Display a paginated list of stockins.
     */
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Stockin::when($request->search, function (Builder $query, string $search) {
            $query->where('kode_tiket', 'like', "%$search%")
                ->orWhere('deskripsi', 'like', "%$search%")
                ->orWhere('datetime', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    /**
     * Store a newly created stockin.
     */
    public function store(StoreStockinRequest $request)
    {
        $validatedData = $request->validated();

        // Create new stockin
        $stockin = Stockin::create($validatedData);

        return response()->json([
            'success' => true,
            'stockin' => $stockin
        ]);
    }

    /**
     * Display the specified stockin.
     */
    public function show(Stockin $stockin)
    {
        return response()->json(['success' => true, 'stockin' => $stockin]);
    }

    /**
     * Update the specified stockin.
     */
    public function update(UpdateStockinRequest $request, Stockin $stockin)
    {
        $validatedData = $request->validated();

        // Update stockin
        $stockin->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Stockin berhasil diupdate',
            'stockin' => $stockin
        ]);
    }

    /**
     * Update the quantity of the specified stockin.
     */
    public function updateStok(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:0',  // Validasi input jumlah
        ]);

        $stockin = Stockin::findOrFail($id); // Find stockin by ID

        // Update quantity
        $stockin->jumlah = $request->input('jumlah');
        $stockin->save(); // Save changes

        return response()->json(['success' => true, 'message' => 'Stok berhasil diperbarui']);
    }

    /**
     * Remove the specified stockin.
     */
    public function destroy(Stockin $stockin)
    {
        $stockin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stockin berhasil dihapus'
        ]);
    }
}
