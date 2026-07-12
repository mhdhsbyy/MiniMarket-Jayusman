<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Cashier Panel
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Detail Transaksi
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Informasi lengkap transaksi dan daftar produk yang dibeli pelanggan.
                    </p>
                </div>

                <div>
                    <a href="{{ route('cashier.transactions.history') }}"
                        class="inline-flex items-center px-6 py-3 rounded-2xl bg-slate-200 text-slate-700 font-black hover:bg-slate-300 transition">
                        ← Kembali
                    </a>
                </div>
            </div>

            {{-- Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-6">
                    <p class="text-xs font-black text-slate-500 uppercase tracking-wider">
                        Total Bayar
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-emerald-700">
                        Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-6">
                    <p class="text-xs font-black text-slate-500 uppercase tracking-wider">
                        Uang Dibayar
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-slate-900">
                        Rp {{ number_format($transaction->uang_dibayar, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-6">
                    <p class="text-xs font-black text-slate-500 uppercase tracking-wider">
                        Kembalian
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-slate-900">
                        Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-6">
                    <p class="text-xs font-black text-slate-500 uppercase tracking-wider">
                        Status
                    </p>

                    <div class="mt-4">
                        @if ($transaction->status === 'success')
                            <span
                                class="inline-flex px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-sm font-black">
                                Success
                            </span>
                        @elseif($transaction->status === 'pending')
                            <span
                                class="inline-flex px-4 py-2 rounded-full bg-amber-100 text-amber-700 text-sm font-black">
                                Pending
                            </span>
                        @else
                            <span
                                class="inline-flex px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-black">
                                Cancelled
                            </span>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Informasi Transaksi --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div
                    class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden lg:col-span-1">

                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-black text-slate-900">
                            Informasi Transaksi
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Detail transaksi yang tersimpan.
                        </p>
                    </div>

                    <div class="p-6 space-y-6">

                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase">
                                ID Transaksi
                            </p>

                            <p class="mt-2 text-lg font-black text-slate-900">
                                #TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase">
                                Tanggal
                            </p>

                            <p class="mt-2 text-lg font-black text-slate-900">
                                {{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase">
                                Kasir
                            </p>

                            <p class="mt-2 text-lg font-black text-slate-900">
                                {{ $transaction->cashier->first_name ?? '-' }}
                                {{ $transaction->cashier->last_name ?? '' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase">
                                Cabang
                            </p>

                            <p class="mt-2 text-lg font-black text-slate-900">
                                {{ $transaction->branch->nama ?? '-' }}
                            </p>

                            <p class="text-sm text-slate-500 mt-1">
                                {{ $transaction->branch->alamat ?? '-' }}
                            </p>
                        </div>

                    </div>
                </div>

                {{-- Detail Produk --}}
                <div
                    class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden lg:col-span-2">

                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-black text-slate-900">
                            Produk Dibeli
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Daftar produk yang masuk ke transaksi.
                        </p>
                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                        Produk
                                    </th>

                                    <th
                                        class="px-6 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                                        Harga
                                    </th>

                                    <th
                                        class="px-6 py-4 text-center text-xs font-black uppercase tracking-wider text-slate-500">
                                        Qty
                                    </th>

                                    <th
                                        class="px-6 py-4 text-right text-xs font-black uppercase tracking-wider text-slate-500">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">

                                @forelse ($transaction->details as $detail)
                                    <tr class="hover:bg-slate-50 transition">

                                        <td class="px-6 py-5">
                                            <p class="font-black text-slate-900">
                                                {{ $detail->product->nama ?? '-' }}
                                            </p>

                                            <p class="text-sm text-slate-500">
                                                {{ $detail->product->kode ?? '-' }}
                                            </p>
                                        </td>

                                        <td class="px-6 py-5 font-black text-emerald-700">
                                            Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                        </td>

                                        <td class="px-6 py-5 text-center">
                                            <span
                                                class="inline-flex items-center justify-center min-w-[50px] px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-black">
                                                {{ $detail->jumlah }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-5 text-right font-black text-slate-900">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-16 text-center text-slate-500 font-bold">
                                            Tidak ada produk dalam transaksi ini.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                            <tfoot class="bg-slate-50 border-t border-slate-200">
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-5 text-right font-black text-slate-600 uppercase">
                                        Total
                                    </td>

                                    <td
                                        class="px-6 py-5 text-right text-2xl font-black text-emerald-700">
                                        Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
