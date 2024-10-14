<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{
    protected $carts;

    public function __construct($carts)
    {
        parent::__construct();

        $this->carts = $carts;
    }

    public function getSnapToken()
    {
        $params = [
            'transaction_details' => [
                'order_id' => $this->carts->id,
                'gross_amount' => $this->carts->total_harga,
            ],
            
        ];

        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }
}