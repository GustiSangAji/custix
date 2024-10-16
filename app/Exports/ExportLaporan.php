<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ExportLaporan implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
{
    $data = Laporan::when($request->search, function (Builder $query, string $search) {
        $query->where('nama_tiket', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orWhere('tanggal_pembelian', 'like', "%$search%");
    })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);
    return $data;
}
}
