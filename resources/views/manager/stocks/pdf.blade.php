<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Cabang</title>

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

        .kop p {
            margin: 5px 0 0;
            text-align: center;
            font-size: 12px;
            color: #475569;
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

        .status-normal {
            color: #047857;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-menipis {
            color: #b45309;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-habis {
            color: #b91c1c;
            font-weight: bold;
            text-transform: uppercase;
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
        <h1>Laporan Stok Mini Market Jayusman</h1>
        <p>Cabang {{ $manager->branch->kota ?? '-' }}</p>
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
            <td>{{ now()->format('d M Y H:i') }}</td>
        </tr>

        @if (request('category_id'))
            <tr>
                <td class="label">Kategori</td>
                <td class="separator">:</td>
                <td>{{ $stocks->first()?->product?->category?->nama ?? '-' }}</td>
            </tr>
        @endif

        @if (request('status_stok'))
            <tr>
                <td class="label">Status Stok</td>
                <td class="separator">:</td>
                <td>{{ ucfirst(request('status_stok')) }}</td>
            </tr>
        @endif
    </table>

    <table class="summary">
        <tr>
            <td>
                <span>Total Produk</span>
                {{ $stocks->count() }}
            </td>

            <td>
                <span>Total Stok</span>
                {{ number_format($stocks->sum('jumlah_stok'), 0, ',', '.') }}
            </td>

            <td>
                <span>Stok Menipis</span>
                {{ $stocks->whereBetween('jumlah_stok', [1, 10])->count() }}
            </td>

            <td>
                <span>Stok Habis</span>
                {{ $stocks->where('jumlah_stok', 0)->count() }}
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Produk</th>
                <th width="22%">Kategori</th>
                <th width="13%" class="text-center">Stok</th>
                <th width="13%">Satuan</th>
                <th width="17%">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($stocks as $stock)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>

                    <td>
                        {{ $stock->product->nama ?? '-' }}
                    </td>

                    <td>
                        {{ $stock->product->category->nama ?? '-' }}
                    </td>

                    <td class="text-center">
                        {{ number_format($stock->jumlah_stok, 0, ',', '.') }}
                    </td>

                    <td>
                        {{ $stock->product->satuan ?? '-' }}
                    </td>

                    <td>
                        @if ($stock->jumlah_stok == 0)
                            <span class="status-habis">Habis</span>
                        @elseif ($stock->jumlah_stok <= 10)
                            <span class="status-menipis">Menipis</span>
                        @else
                            <span class="status-normal">Normal</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        Tidak ada data stok.
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
