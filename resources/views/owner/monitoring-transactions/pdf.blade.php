<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Owner</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #0f172a;
            margin: 30px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .header p {
            margin: 5px 0 0;
            color: #475569;
        }

        .info {
            margin-bottom: 18px;
            line-height: 1.7;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary td {
            border: 1px solid #cbd5e1;
            padding: 10px;
            font-weight: bold;
            width: 33.33%;
        }

        .summary span {
            display: block;
            color: #64748b;
            font-size: 11px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #0f172a;
            color: white;
            padding: 9px;
            text-align: left;
            font-size: 11px;
        }

        td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .status-success {
            color: #047857;
            font-weight: bold;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 24px;
            text-align: right;
            font-size: 11px;
            color: #64748b;
        }

        .signature {
            margin-top: 35px;
            width: 220px;
            margin-left: auto;
            text-align: center;
            font-size: 12px;
            color: #0f172a;
        }

        .signature-space {
            height: 65px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>LAPORAN TRANSAKSI</h1>
        <p>Minimarket Pak Jayusman</p>
    </div>

    <div class="info">
        <strong>Periode:</strong> {{ $periode }} <br>
        <strong>Cabang:</strong> {{ $branch ? $branch->nama : 'Semua Cabang' }} <br>
        <strong>Tanggal Cetak:</strong> {{ now()->format('d M Y H:i') }}
    </div>

    <table class="summary">
        <tr>
            <td>
                <span>Total Transaksi</span>
                {{ $totalTransaksi }}
            </td>

            <td>
                <span>Total Pendapatan</span>
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </td>

            <td>
                <span>Cabang Terbaik</span>
                @if ($cabangTerbaik)
                    {{ $cabangTerbaik->nama }} <br>
                    Rp {{ number_format($cabangTerbaik->total_pendapatan, 0, ',', '.') }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="16%">Tanggal</th>
                <th width="18%">Cabang</th>
                <th width="16%">Kasir</th>
                <th width="15%" class="text-right">Total</th>
                <th width="15%" class="text-right">Dibayar</th>
                <th width="15%" class="text-right">Kembalian</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y H:i') }}
                    </td>

                    <td>
                        {{ $transaction->branch->nama ?? '-' }} <br>
                        <span style="color:#64748b;">
                            {{ $transaction->branch->kota ?? '-' }}
                        </span>
                    </td>

                    <td>
                        {{ $transaction->cashier
                            ? $transaction->cashier->first_name . ' ' . $transaction->cashier->last_name
                            : '-' }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($transaction->uang_dibayar, 0, ',', '.') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Tidak ada data transaksi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature">
        <p>Owner</p>
        <div class="signature-space"></div>
        <p><strong>Pak Jayusman</strong></p>
    </div>

</body>

</html>
