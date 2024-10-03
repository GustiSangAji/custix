<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use App\Http\Requests\StoreTiketRequest;
use App\Http\Requests\UpdateTiketRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TiketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Tiket::when($request->status, function (Builder $query, string $status) {
                $query->where('status', $status);
            })->get()
        ]);
    }

    /**
     * Display a paginated list of the resource.
     */
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Tiket::when($request->search, function (Builder $query, string $search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('place', 'like', "%$search%")
                ->orWhere('datetime', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTiketRequest $request)
    {
        $validatedData = $request->validated();

        // Jika ada file gambar, simpan
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('tikets', 'public');
        }

        $tiket = Tiket::create($validatedData);

        return response()->json([
            'success' => true,
            'tiket' => $tiket
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Tiket $tiket)
{
    return response()->json(['success' => true, 'tiket' => $tiket]);
}




    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTiketRequest $request, Tiket $tiket)
{
    $validatedData = $request->validated();

    // Jika ada file gambar, simpan dan perbarui path gambar
    if ($request->hasFile('image')) {
        $validatedData['image'] = $request->file('image')->store('tikets', 'public');
    }

    // Update data tiket
    $tiket->update($validatedData);

    return response()->json([
        'success' => true,
        'message' => 'Tiket berhasil diupdate',
        'tiket' => $tiket
    ]);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tiket $tiket)
    {
        if ($tiket->image) {
            Storage::disk('public')->delete($tiket->image);
        }

        $tiket->delete();

        return response()->json([
            'success' => true
        ]);
    }
}

