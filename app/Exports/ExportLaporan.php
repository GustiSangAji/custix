<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportLaporan implements FromCollection, WithHeadings, WithStyles, WithEvents
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
        // Fungsi untuk memformat angka ke Rupiah
        $formatRupiah = function ($value) {
            return 'Rp ' . number_format($value, 0, ',', '.');
        };

        // Filter hanya untuk status "paid"
        $cartsPaid = $this->carts->where('status', 'Paid');

        // Hitung total harga dari status "paid" saja
        $totalHargaPaid = $cartsPaid->sum('total_harga');

        // Ambil data carts yang sudah difilter di controller
        $data = $this->carts->map(function ($cart, $index) use ($formatRupiah) {
            return [
                'no' => $index + 1, // nomor urut
                'order_id' => $cart->order_id,
                'email' => $cart->user->email ?? 'N/A',
                'nama_tiket' => $cart->ticket->name ?? 'N/A',
                'jumlah' => $cart->jumlah_pemesanan,
                'harga' => $formatRupiah($cart->total_harga), // Format rupiah
                'tanggal_pembelian' => $cart->created_at->format('Y-m-d'),
                'status' => $cart->status,
            ];
        })->toArray();

        // Tambahkan total harga dari status "paid" ke dalam array dengan format rupiah
        $data[] = [
            'no' => '',
            'order_id' => '',
            'email' => '',
            'nama_tiket' => '',
            'jumlah' => '',
            'harga' => $formatRupiah($totalHargaPaid), // Total harga dari paid diformat ke rupiah
            'tanggal_pembelian' => '',
            'status' => 'Total Harga Paid', // Label
        ];

        return collect($data);
    }

    // Menambahkan judul kolom
    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'Email',
            'Nama Tiket',
            'Jumlah Pemesanan',
            'Total Harga',
            'Tanggal Pembelian',
            'Status'
        ];
    }

    // Menambahkan style ke worksheet
    public function styles(Worksheet $sheet)
    {
        // Set heading style
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1:H1')->getFill()->getStartColor()->setARGB('FF0070C0');
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set auto size kolom
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set alignment for all cells
        $sheet->getStyle('A2:H' . ($this->carts->count() + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Styling untuk baris total harga
        $lastRow = $this->carts->count() + 2; // Baris di bawah data
        $sheet->getStyle('F' . $lastRow)->getFont()->setBold(true); // Total harga bold
        $sheet->getStyle('G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Rata kanan
    }

    // Mengatur events jika diperlukan
    public function registerEvents(): array
    {
        return [
            // Events yang ingin digunakan, seperti styling atau lainnya
        ];
    }
}
