<?php

namespace App\Http\Controllers;

use App\Models\Stockin;
use Illuminate\Http\Request;

class StockinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $stockins = StockIn::all(); // Mengambil semua data dari tabel stockins

        return response()->json($stockins); // Mengembalikan data dalam format JSON
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(stockin $stockin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stockin $stockin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, stockin $stockin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(stockin $stockin)
    {
        //
    }
}
