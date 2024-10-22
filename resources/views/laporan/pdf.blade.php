<!-- resources/views/laporan/pdf.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Transaksi</h2>
        <p>Custix - Sistem Ticketing</p>
        <p>Tanggal: {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Nama Tiket</th>
                <th>Jumlah Pemesanan</th>
                <th>Total Harga</th>
                <th>Tanggal Pembelian</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->ticket->name }}</td>
                <td>{{ $item->jumlah_pemesanan }}</td>
                <td>{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                <td>{{ $item->status == 'Paid' ? 'Sudah Dibayar' : 'Belum Dibayar' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
