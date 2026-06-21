<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            @php
                $branchName = Auth::user()->branch->nama ?? 'Cabang';

                $branchInitial = collect(explode(' ', $branchName))
                    ->filter()
                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                    ->take(2)
                    ->implode('');
            @endphp

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Cashier Dashboard
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Selamat Datang, {{ Auth::user()->first_name }} 🖐️
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Kelola transaksi penjualan pada cabang yang Anda tempati.
                    </p>
                </div>

                <div class="flex items-center gap-4 lg:text-right">

                    <div class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center">
                        <span class="text-emerald-700 text-xl font-black">
                            {{ $branchInitial }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-black text-slate-900">
                            {{ $branchName }}
                        </h3>

                        <p class="text-sm text-emerald-700">
                            Kasir Cabang
                        </p>
                    </div>

                </div>

            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Transaksi Hari Ini
                    </p>

                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $totalTransaksiHariIni }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Transaksi yang dibuat hari ini
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Pendapatan Hari Ini
                    </p>

                    <h2 class="text-3xl font-black text-emerald-700 mt-3">
                        Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Total transaksi selesai
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Produk Cabang
                    </p>

                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $totalProdukCabang }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Produk tersedia di cabang
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Stok Menipis
                    </p>

                    <h2 class="text-4xl font-black text-amber-600 mt-3">
                        {{ $stokMenipis }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Produk dengan stok rendah
                    </p>
                </div>

            </div>

            {{-- Aksi Cepat --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">
                        Aksi Cepat
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Menu utama untuk proses transaksi kasir.
                    </p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                    <a href="{{ route('cashier.transactions.index') }}"
                        class="flex items-center justify-between gap-4 p-5 rounded-2xl bg-emerald-50 border border-emerald-100 hover:bg-emerald-100 transition">
                        <div>
                            <p class="font-black text-emerald-800">
                                Transaksi Baru
                            </p>

                            <p class="text-sm text-emerald-700 mt-1">
                                Input pembelian pelanggan.
                            </p>
                        </div>

                        <span class="text-3xl font-black text-emerald-700">
                            →
                        </span>
                    </a>

                    <a href="{{ route('cashier.transactions.history') }}"
                        class="flex items-center justify-between gap-4 p-5 rounded-2xl bg-emerald-50 border border-emerald-100 hover:bg-emerald-100 transition">
                        <div>
                            <p class="font-black text-emerald-800">
                                Riwayat Transaksi
                            </p>

                            <p class="text-sm text-emerald-700 mt-1">
                                Lihat transaksi yang sudah dibuat.
                            </p>
                        </div>

                        <span class="text-3xl font-black text-emerald-700">
                            →
                        </span>
                    </a>

                </div>
            </div>

            {{-- Transaksi Terbaru --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Transaksi Terbaru
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            5 transaksi terakhir yang Anda buat.
                        </p>
                    </div>

                    <a href="{{ route('cashier.transactions.history') }}"
                        class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                        Lihat Semua
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kode</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Total</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($transaksiTerbaru as $transaction)
                                @php
                                    $kodeTransaksi = 'TRX-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);
                                    $tanggalTransaksi = \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y H:i');
                                @endphp

                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 font-black text-slate-900">
                                        {{ $kodeTransaksi }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $tanggalTransaksi }}
                                    </td>

                                    <td class="px-6 py-5 font-black text-emerald-700">
                                        Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($transaction->status == 'success')
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-black">
                                                Batal
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5 text-right">
                                        <a href="{{ route('cashier.transactions.show', $transaction->id) }}"
                                            class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-slate-500">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
