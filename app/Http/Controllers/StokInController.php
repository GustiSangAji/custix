<?php

namespace App\Http\Controllers;

use App\Models\StokIn;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StokInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $stokIn = StokIn::with('tiket')
            ->when($request->search, function ($query, $search) {
                return $query->whereHas('tiket', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })
            ->orderBy('added_at', 'desc')
            ->paginate(10);

        return response()->json($stokIn);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required|exists:tiket,id',
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

        // Create a new StokIn record
        $stokIn = StokIn::create([
            'ticket_id' => $request->input('ticket_id'),
            'description' => $request->input('description'),
            'added_at' => $request->input('added_at'),
            'amount' => $request->input('amount'),
        ]);

        // Update the stock in the associated Tiket model
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
            'ticket_id' => 'required|exists:tiket,id',
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

        // Calculate the difference in stock
        $previousAmount = $stokIn->amount;
        $difference = $request->input('amount') - $previousAmount;

        // Update StokIn data
        $stokIn->update([
            'ticket_id' => $request->input('ticket_id'),
            'description' => $request->input('description'),
            'added_at' => $request->input('added_at'),
            'amount' => $request->input('amount'),
        ]);

        // Update stock in the Tiket model
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

        // Update the stock in the Tiket model before deleting
        $tiket = Tiket::find($stokIn->ticket_id);
        $tiket->quantity -= $stokIn->amount;
        $tiket->save();

        // Delete the StokIn record
        $stokIn->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stok tiket berhasil dihapus',
        ]);
    }
}
