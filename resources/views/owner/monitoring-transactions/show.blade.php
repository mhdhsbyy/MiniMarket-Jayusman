<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-5xl mx-auto px-6 py-8">

            <div class="mb-8 flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-black text-emerald-700 uppercase tracking-widest">
                        Detail Transaksi
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Transaksi #{{ $transaction->id }}
                    </h1>

                    <p class="text-slate-500 mt-2">
                        Detail transaksi dan produk yang dibeli.
                    </p>
                </div>

                <a href="{{ route('owner.monitoring-transactions.index') }}"
                    class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-black">
                    Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                <div class="bg-white rounded-[1.5rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-bold text-slate-500">Total Bayar</p>
                    <h2 class="text-2xl font-black text-emerald-700 mt-2">
                        Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-[1.5rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-bold text-slate-500">Uang Dibayar</p>
                    <h2 class="text-2xl font-black text-slate-900 mt-2">
                        Rp {{ number_format($transaction->uang_dibayar, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-[1.5rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-bold text-slate-500">Kembalian</p>
                    <h2 class="text-2xl font-black text-slate-900 mt-2">
                        Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            <div class="bg-white rounded-[1.5rem] p-6 border border-slate-200 shadow-sm mb-6">
                <h2 class="text-lg font-black text-slate-900 mb-5">
                    Informasi Transaksi
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <p class="text-sm font-bold text-slate-500">Cabang</p>
                        <p class="font-black text-slate-900 mt-1">
                            {{ $transaction->branch->nama ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-slate-500">Kasir</p>
                        <p class="font-black text-slate-900 mt-1">
                            {{ $transaction->cashier->first_name ?? '-' }}
                            {{ $transaction->cashier->last_name ?? '' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-slate-500">Tanggal</p>
                        <p class="font-black text-slate-900 mt-1">
                            {{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->translatedFormat('d F Y H:i') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-slate-500">Status</p>
                        <p class="font-black text-emerald-700 mt-1">
                            {{ ucfirst($transaction->status) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[1.5rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h2 class="text-lg font-black text-slate-900">
                        Produk Dibeli
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Daftar produk pada transaksi ini.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[750px]">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-black text-slate-500 uppercase">Produk</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-slate-500 uppercase">Jumlah</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-slate-500 uppercase">Harga</th>
                                <th class="px-5 py-4 text-left text-xs font-black text-slate-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($transaction->details as $detail)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-5 py-4">
                                        <p class="font-black text-slate-900">
                                            {{ $detail->product->nama ?? '-' }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            Kode: {{ $detail->product->kode ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 font-bold text-slate-700">
                                        {{ $detail->jumlah }}
                                    </td>

                                    <td class="px-5 py-4 font-bold text-slate-700">
                                        Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                    </td>

                                    <td class="px-5 py-4 font-black text-emerald-700">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-slate-500">
                                        Tidak ada detail produk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot class="bg-slate-50">
                            <tr>
                                <td colspan="3" class="px-5 py-4 text-right font-black text-slate-900">
                                    Total
                                </td>
                                <td class="px-5 py-4 font-black text-emerald-700">
                                    Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
