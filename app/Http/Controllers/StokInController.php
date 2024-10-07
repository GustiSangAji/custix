<?php

namespace App\Http\Controllers;

use App\Models\StokIn;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StokInController extends Controller
{
    /**
     * Display a paginated listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per', 10); // Default 10 per page
        $search = $request->input('search');

        $stokIn = StokIn::with('tiket')
            ->when($search, function ($query, $search) {
                return $query->whereHas('tiket', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })
            ->orderBy('added_at', 'desc')
            ->paginate($perPage);

        return response()->json($stokIn);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required|exists:tikets,id',
            'description' => 'required|string|max:255',
            'added_at' => 'required|date',
            'amount' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Menyimpan stok masuk
        $stokIn = StokIn::create($request->only('ticket_id', 'description', 'added_at', 'amount'));

        // Update stok pada Tiket
        $tiket = Tiket::find($request->input('ticket_id'));
        $tiket->quantity += $request->input('amount');
        $tiket->save();

        return response()->json([
            'success' => true,
            'message' => 'Stok tiket berhasil ditambahkan',
            'stokIn' => $stokIn,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $stokIn = StokIn::with('tiket')->findOrFail($id);

        return response()->json([
            'success' => true,
            'stokIn' => $stokIn,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $stokIn = StokIn::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required|exists:tikets,id',
            'description' => 'required|string|max:255',
            'added_at' => 'required|date',
            'amount' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Menghitung perubahan stok
        $previousAmount = $stokIn->amount;
        $difference = $request->input('amount') - $previousAmount;

        // Update stok masuk
        $stokIn->update($request->only('ticket_id', 'description', 'added_at', 'amount'));

        // Update stok pada Tiket
        $tiket = Tiket::find($request->input('ticket_id'));
        $tiket->quantity += $difference;
        $tiket->save();

        return response()->json([
            'success' => true,
            'message' => 'Stok tiket berhasil diperbarui',
            'stokIn' => $stokIn,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stokIn = StokIn::findOrFail($id);

        // Kurangi stok di Tiket
        $tiket = Tiket::find($stokIn->ticket_id);
        $tiket->quantity -= $stokIn->amount;
        $tiket->save();

        // Hapus stok masuk
        $stokIn->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stok tiket berhasil dihapus',
        ]);
    }
}
