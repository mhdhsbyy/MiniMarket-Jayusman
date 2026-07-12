<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok</title>

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
            width: 25%;
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
        <h1>LAPORAN STOK BARANG</h1>
        <p>Minimarket Pak Jayusman</p>
    </div>

    <div class="info">
        <p><strong>Cabang:</strong> {{ $branch ? $branch->nama : 'Semua Cabang' }}</p>
        <p><strong>Kategori:</strong> {{ $category ? $category->nama : 'Semua Kategori' }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ now()->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <p>Total Produk</p>
            <h2>{{ $totalProduk }}</h2>
        </div>

        <div class="summary-item">
            <p>Total Stok</p>
            <h2>{{ number_format($totalStok, 0, ',', '.') }}</h2>
        </div>

        <div class="summary-item">
            <p>Stok Menipis</p>
            <h2>{{ $stokMenipis }}</h2>
        </div>

        <div class="summary-item">
            <p>Stok Habis</p>
            <h2>{{ $stokHabis }}</h2>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Kode</th>
                <th>Kategori</th>
                <th>Cabang</th>
                <th class="right">Stok</th>
                <th class="center">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($stocks as $stock)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $stock->product->nama ?? '-' }}</td>
                    <td>{{ $stock->product->kode ?? '-' }}</td>
                    <td>{{ $stock->product->category->nama ?? '-' }}</td>
                    <td>{{ $stock->branch->nama ?? '-' }}</td>
                    <td class="right">{{ number_format($stock->jumlah_stok, 0, ',', '.') }}</td>
                    <td class="center">
                        @if ($stock->jumlah_stok <= 0)
                            Habis
                        @elseif ($stock->jumlah_stok <= 30)
                            Menipis
                        @else
                            Aman
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">
                        Tidak ada data stok.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Owner</p>
            <div class="signature-space"></div>
            <p><strong>Pak Jayusman</strong></p>
        </div>
    </div>
</body>
</html>
