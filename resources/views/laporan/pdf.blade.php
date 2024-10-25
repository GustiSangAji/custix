<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Custix</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f8f9fa;
        }
        .container {
            width: 85%;
            margin: auto;
            padding-top: 20px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            color: #4a4a4a;
        }
        .header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th {
            background-color: #007bff;
            color: #fff;
            padding: 12px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }
        td {
            padding: 10px;
            font-size: 13px;
            text-align: center;
            color: #495057;
        }
        td.right {
            text-align: right;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tfoot td {
            font-weight: bold;
            font-size: 14px;
            color: #343a40;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
        }
        .highlight {
            color: #28a745;
        }
        /* Mengatur lebar kolom */
        .column-total-harga {
            width: 25%; /* Lebih lebar */
        }
        .column-jumlah-pesanan {
            width: 10%; /* Lebih kecil */
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h2>Laporan Penjualan</h2>
            <p>Custix - Sistem Ticketing</p>
            <p>Tanggal: {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Nama Tiket</th>
                    <th class="column-jumlah-pesanan">Jumlah Pemesanan</th>
                    <th class="column-total-harga">Total Harga</th>
                    <th>Tanggal Pembelian</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporan as $item)
                <tr>
                    <td>{{ $item->order_id }}</td>
                    <td>{{ $item->ticket->name }}</td>
                    <td class="column-jumlah-pesanan">{{ $item->jumlah_pemesanan }}</td>
                    <td class="right column-total-harga">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                    <td class="highlight">Sudah Dibayar</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;">Total Keseluruhan</td>
                    <td class="right column-total-harga" colspan="2">Rp {{ number_format($laporan->sum('total_harga'), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>Total Transaksi: Rp {{ number_format($laporan->sum('total_harga'), 0, ',', '.') }}</p>
        </div>
    </div>

</body>
</html>
