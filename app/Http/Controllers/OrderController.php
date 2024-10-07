<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Midtrans\CreateSnapTokenService;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Order::when($request->status, function (Builder $query, string $status) {
                $query->where('status', $status);
            })->get()
        ]);
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
    public function show(Order $order)
    {
        $snapToken = $order->snap_token;
        if (is_null($snapToken)) {
            // If snap token is still NULL, generate snap token and save it to database

            $midtrans = new CreateSnapTokenService($order);
            $snapToken = $midtrans->getSnapToken();

            $order->snap_token = $snapToken;
            $order->save();
        }

        return response()->json(['order' => $order, 'snap_token' => $snapToken]); // Return as JSON
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {

    }
}
