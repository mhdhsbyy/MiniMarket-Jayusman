<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi</title>

    <style>
        @page {
            margin: 8px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
            margin: 0;
            padding: 0;
        }

        .receipt {
            width: 100%;
        }

        .center {
            text-align: center;
        }

        .store-name {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .small {
            font-size: 9px;
            line-height: 1.35;
        }

        .line {
            border-top: 1px dashed #111827;
            margin: 7px 0;
        }

        .row {
            width: 100%;
            display: table;
            margin-bottom: 3px;
        }

        .left {
            display: table-cell;
            text-align: left;
        }

        .right {
            display: table-cell;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .product-name {
            font-weight: bold;
            padding-top: 4px;
        }

        .total {
            font-size: 12px;
            font-weight: bold;
        }

        .footer {
            margin-top: 9px;
            text-align: center;
            font-size: 9px;
            line-height: 1.4;
        }
    </style>
</head>

<body>
    <div class="receipt">

        <div class="center">
            <div class="store-name">JAYUSMART</div>

            <div class="small">
                {{ $transaction->branch->nama ?? 'Cabang' }}
            </div>

            <div class="small">
                {{ $transaction->branch->alamat ?? '-' }}
            </div>
        </div>

        <div class="line"></div>

        <div class="row">
            <div class="left">No</div>
            <div class="right">#TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="row">
            <div class="left">Tanggal</div>
            <div class="right">
                {{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->translatedFormat('d F Y H:i') }}
            </div>
        </div>

        <div class="row">
            <div class="left">Kasir</div>
            <div class="right">
                {{ $transaction->cashier->first_name ?? '-' }}
                {{ $transaction->cashier->last_name ?? '' }}
            </div>
        </div>

        <div class="line"></div>

        <table>
            @foreach ($transaction->details as $detail)
                <tr>
                    <td colspan="2" class="product-name">
                        {{ $detail->product->nama ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td>
                        {{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                    </td>

                    <td style="text-align: right;">
                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="line"></div>

        <div class="row total">
            <div class="left">TOTAL</div>
            <div class="right">
                Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
            </div>
        </div>

        <div class="row">
            <div class="left">Bayar</div>
            <div class="right">
                Rp {{ number_format($transaction->uang_dibayar, 0, ',', '.') }}
            </div>
        </div>

        <div class="row">
            <div class="left">Kembali</div>
            <div class="right">
                Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}
            </div>
        </div>

        <div class="line"></div>

        <div class="footer">
            Terima kasih sudah berbelanja<br>
            Barang yang sudah dibeli tidak dapat dikembalikan
        </div>

    </div>
</body>

</html>
