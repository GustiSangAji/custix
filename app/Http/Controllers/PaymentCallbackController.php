<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('Midtrans Callback Request: ', $request->all());

        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $serverKey = config('midtrans.MIDTRANS_SERVER_KEY');
        $receivedSignatureKey = $request->signature_key;

        // Create the correct string to hash
        $stringToHash = $orderId . $statusCode . $grossAmount . $serverKey;

        // Generate the correct hash
        $calculatedSignatureKey = hash("sha512", $stringToHash);

        Log::info('String to hash: ' . $stringToHash);
        Log::info('Calculated signature key: ' . $calculatedSignatureKey);
        Log::info('Received signature key: ' . $receivedSignatureKey);

        if ($calculatedSignatureKey === $receivedSignatureKey) {
            if ($request->transaction_status == 'settlement') {
                $cart = Cart::find($orderId);

                if ($cart) {
                    $cart->update(['status' => 'Paid']);
                    Log::info('Order ' . $orderId . ' status updated to Paid');
                } else {
                    Log::error('Order with id ' . $orderId . ' not found');
                }
            } else {
                Log::info('Transaction status is not settlement: ' . $request->transaction_status);
            }
        } else {
            Log::error('Invalid signature for order_id: ' . $orderId);
        }

        return response()->json(['status' => 'ok'], 200);
    }
}