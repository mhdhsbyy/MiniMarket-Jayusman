<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Cabang</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #0f172a;
            margin: 30px;
            font-size: 12px;
        }

        .kop {
            width: 100%;
            border-bottom: 3px solid #0f172a;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .kop h1 {
            margin: 0;
            text-align: center;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .kop h2 {
            margin: 5px 0 0;
            text-align: center;
            font-size: 16px;
            text-transform: uppercase;
        }

        .kop p {
            margin: 5px 0 0;
            text-align: center;
            font-size: 12px;
            color: #475569;
        }

        .title {
            text-align: center;
            margin: 22px 0 18px;
        }

        .title h3 {
            display: inline-block;
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            border-bottom: 1px solid #0f172a;
            padding-bottom: 4px;
        }

        .info {
            width: 100%;
            margin-bottom: 18px;
            font-size: 12px;
        }

        .info td {
            border: none;
            padding: 3px 0;
        }

        .info .label {
            width: 120px;
            font-weight: bold;
        }

        .info .separator {
            width: 12px;
            text-align: center;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .summary td {
            border: 1px solid #cbd5e1;
            padding: 10px;
            font-weight: bold;
            width: 25%;
        }

        .summary span {
            display: block;
            color: #64748b;
            font-size: 11px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .data-table th {
            background: #0f172a;
            color: white;
            padding: 9px;
            text-align: left;
            font-size: 11px;
            border: 1px solid #0f172a;
        }

        .data-table td {
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

        .status-cancelled {
            color: #b91c1c;
            font-weight: bold;
            text-transform: uppercase;
        }

        .footer-note {
            margin-top: 18px;
            font-size: 11px;
            color: #64748b;
            text-align: right;
        }

        .signature-wrapper {
            width: 100%;
            margin-top: 35px;
        }

        .signature {
            width: 230px;
            margin-left: auto;
            text-align: center;
            font-size: 12px;
        }

        .signature-space {
            height: 65px;
        }
    </style>
</head>

<body>

    <div class="kop">
        <h1>Laporan Transaksi Mini Market Jayusman</h1>
        <p>{{ $manager->branch->nama ?? 'Cabang' }}</p>
    </div>

    <table class="info">
        <tr>
            <td class="label">Manager</td>
            <td class="separator">:</td>
            <td>{{ $manager->first_name }} {{ $manager->last_name }}</td>
        </tr>
        <tr>
            <td class="label">Cabang</td>
            <td class="separator">:</td>
            <td>{{ $manager->branch->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td class="separator">:</td>
            <td>{{ $manager->branch->alamat ?? 'Alamat cabang belum tersedia' }}</td>
        </tr>
        <tr>
            <td class="label">No Telp</td>
            <td class="separator">:</td>
            <td>{{ $manager->no_hp ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Cetak</td>
            <td class="separator">:</td>
            <td>{{ now()->translatedFormat('d F Y H:i') }}</td>
        </tr>

        @if (request('start_date') || request('end_date'))
            <tr>
                <td class="label">Periode</td>
                <td class="separator">:</td>
                <td>
                    {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d F Y') : 'Awal' }}
                    -
                    {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d F Y') : 'Akhir' }}
                </td>
            </tr>
        @endif

        @if (request('periode') && request('periode') !== 'semua')
            <tr>
                <td class="label">Filter</td>
                <td class="separator">:</td>
                <td>{{ ucfirst(request('periode')) }}</td>
            </tr>
        @endif
    </table>

    <table class="summary">
        <tr>
            <td>
                <span>Total Transaksi</span>
                {{ $transactions->count() }}
            </td>
            <td>
                <span>Transaksi Selesai</span>
                {{ $transactions->where('status', 'success')->count() }}
            </td>
            <td>
                <span>Transaksi Batal</span>
                {{ $transactions->where('status', 'cancelled')->count() }}
            </td>
            <td>
                <span>Total Pendapatan</span>
                Rp {{ number_format($transactions->where('status', 'success')->sum('total_bayar'), 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="16%">Tanggal</th>
                <th width="16%">Kasir</th>
                <th width="16%" class="text-right">Total</th>
                <th width="16%" class="text-right">Dibayar</th>
                <th width="16%" class="text-right">Kembalian</th>
                <th width="15%">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->translatedFormat('d F Y H:i') }}
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

                    <td>
                        <span class="{{ $transaction->status == 'success' ? 'status-success' : 'status-cancelled' }}">
                            {{ $transaction->status }}
                        </span>
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

    <div class="signature-wrapper">
        <div class="signature">
            <p>Manager Cabang</p>
            <div class="signature-space"></div>
            <p>
                <strong>{{ $manager->first_name }} {{ $manager->last_name }}</strong>
            </p>
        </div>
    </div>

</body>

</html>
