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

        // Simpan gambar jika ada
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('tikets', 'public');
        }

        // Simpan banner jika ada
        if ($request->hasFile('banner')) {
            $validatedData['banner'] = $request->file('banner')->store('banners', 'public');
        }

        // Buat tiket baru
        $tiket = Tiket::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil ditambahkan',
            'tiket' => $tiket,
        ], 201);
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
    // Validasi data yang diterima dari request
    $validatedData = $request->validated();

    // Jika ada file gambar, simpan dan perbarui path gambar
    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada
        if ($tiket->image) {
            $oldImage = str_replace('/storage/', '', $tiket->image);
            Storage::disk('public')->delete($oldImage);
        }
        $validatedData['image'] = '/storage/' . $request->file('image')->store('imagess', 'public');
    }

    // Jika ada file banner, simpan dan perbarui path banner
    if ($request->hasFile('banner')) {
        // Hapus banner lama jika ada
        if ($tiket->banner) {
            $oldBanner = str_replace('/storage/', '', $tiket->banner);
            Storage::disk('public')->delete($oldBanner);
        }
        $validatedData['banner'] = '/storage/' . $request->file('banner')->store('banners', 'public');
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
     * Update the stock of the specified resource.
     */
    public function updateStok(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',  // Validasi input quantity
        ]);

        $tiket = Tiket::findOrFail($id); // Mencari tiket berdasarkan ID

        // Update jumlah stok
        $tiket->quantity = $request->input('quantity');
        $tiket->save(); // Simpan perubahan ke database

        return response()->json(['success' => true, 'message' => 'Stok tiket berhasil diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tiket $tiket)
    {
        // Hapus gambar jika ada
        if ($tiket->image) {
            Storage::disk('public')->delete($tiket->image);
        }

        // Hapus banner jika ada
        if ($tiket->banner) {
            Storage::disk('public')->delete($tiket->banner);
        }

        $tiket->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
