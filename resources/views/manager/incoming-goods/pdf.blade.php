<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Masuk</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #0f172a;
            margin: 40px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 6px 0 0;
            font-size: 13px;
            color: #475569;
        }

        .info {
            margin-bottom: 20px;
            font-size: 14px;
        }

        .summary {
            display: table;
            width: 100%;
            margin-bottom: 24px;
        }

        .summary-item {
            display: table-cell;
            border: 1px solid #cbd5e1;
            padding: 12px;
            width: 33.33%;
        }

        .summary-item p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }

        .summary-item h2 {
            margin: 6px 0 0;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            background: #f1f5f9;
            text-align: left;
            padding: 9px;
            border: 1px solid #cbd5e1;
        }

        td {
            padding: 9px;
            border: 1px solid #cbd5e1;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        .signature {
            width: 220px;
            text-align: center;
            font-size: 13px;
        }

        .signature-space {
            height: 70px;
        }

        @media print {
            button {
                display: none;
            }

            body {
                margin: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN BARANG MASUK</h1>
        <p>Minimarket Pak Jayusman - {{ $branch->nama ?? 'Cabang' }}</p>
    </div>

    <div class="info">
        <p><strong>Cabang:</strong> {{ $branch->nama ?? '-' }}</p>
        <p><strong>Periode:</strong> {{ $periode }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ now()->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <p>Total Barang Masuk</p>
            <h2>{{ $totalBarangMasuk }}</h2>
        </div>

        <div class="summary-item">
            <p>Total Jumlah Masuk</p>
            <h2>{{ number_format($totalJumlahMasuk, 0, ',', '.') }}</h2>
        </div>

        <div class="summary-item">
            <p>Total Biaya</p>
            <h2>Rp {{ number_format($totalBiaya, 0, ',', '.') }}</h2>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Supplier</th>
                <th class="right">Jumlah</th>
                <th class="right">Harga Beli</th>
                <th class="right">Total Biaya</th>
                <th>Petugas</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($incomingGoods as $incomingGood)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($incomingGood->tanggal_masuk)->translatedFormat('d F Y H:i') }}</td>
                    <td>{{ $incomingGood->product->nama ?? '-' }}</td>
                    <td>{{ $incomingGood->product->supplier->nama ?? '-' }}</td>
                    <td class="right">{{ $incomingGood->jumlah }}</td>
                    <td class="right">Rp {{ number_format($incomingGood->harga_beli, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($incomingGood->harga_beli * $incomingGood->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $incomingGood->user->first_name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">
                        Tidak ada data barang masuk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Manager</p>
            <div class="signature-space"></div>
            <p><strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong></p>
        </div>
    </div>
</body>
</html>
