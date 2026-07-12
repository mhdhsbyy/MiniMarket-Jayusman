<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>

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
            width: 50%;
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
        <h1>LAPORAN TRANSAKSI</h1>
        <p>Minimarket Pak Jayusman</p>
    </div>

    <div class="info">
        <p><strong>Periode:</strong> {{ $periode }}</p>
        <p><strong>Cabang:</strong> {{ $branch ? $branch->nama : 'Semua Cabang' }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <p>Total Transaksi</p>
            <h2>{{ $totalTransaksi }}</h2>
        </div>

        <div class="summary-item">
            <p>Total Pendapatan</p>
            <h2>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Cabang</th>
                <th>Kasir</th>
                <th class="right">Total Bayar</th>
                <th class="right">Uang Dibayar</th>
                <th class="right">Kembalian</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->translatedFormat('d F Y H:i') }}</td>
                    <td>{{ $transaction->branch->nama ?? '-' }}</td>
                    <td>
                        {{ $transaction->cashier->first_name ?? '-' }}
                        {{ $transaction->cashier->last_name ?? '' }}
                    </td>
                    <td class="right">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($transaction->uang_dibayar, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">
                        Tidak ada data transaksi.
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
