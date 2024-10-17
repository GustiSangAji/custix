<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Builder;

    class ExportLaporan implements FromCollection
{
    protected $carts;

    // Constructor untuk menerima collection carts yang sudah difilter
    public function __construct($carts)
    {
        $this->carts = $carts;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Set nomor urut (bisa dihilangkan jika tidak diperlukan)
        DB::statement('set @no=0');

        // Ambil data carts yang sudah difilter di controller
        return collect($this->carts->map(function ($cart, $index) {
            return [
                'no' => $index + 1, // nomor urut
                'order_id' => $cart->order_id,
                'email' => $cart->user->email,
                'nama_tiket' => $cart->ticket->name,
                'jumlah' => $cart->jumlah_pemesanan,    
                'harga' => $cart->total_harga,
                'tanggal_pembelian' => $cart->created_at->format('Y-m-d'),
                'status' => $cart->status,
            ];
        }));
    }
}