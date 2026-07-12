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
                        Warehouse Dashboard
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Selamat Datang, {{ Auth::user()->first_name }} 🖐️
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Kelola stok dan barang masuk pada cabang yang Anda tempati.
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
                            Warehouse Cabang
                        </p>
                    </div>

                </div>

            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Total Produk
                    </p>

                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $totalProduk }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Produk di cabang
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Total Stok
                    </p>

                    <h2 class="text-4xl font-black text-emerald-700 mt-3">
                        {{ number_format($totalStok, 0, ',', '.') }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Akumulasi stok
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
                        Stok 1 - 29
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Stok Habis
                    </p>

                    <h2 class="text-4xl font-black text-red-600 mt-3">
                        {{ $stokHabis }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Stok sama dengan 0
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
                        Menu utama untuk mengelola stok dan barang masuk.
                    </p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                    <a href="{{ route('warehouse.stocks.index') }}"
                        class="flex items-center justify-between gap-4 p-5 rounded-2xl bg-emerald-50 border border-emerald-100 hover:bg-emerald-100 transition">
                        <div>
                            <p class="font-black text-emerald-800">
                                Kelola Stok
                            </p>

                            <p class="text-sm text-emerald-700 mt-1">
                                Lihat dan pantau stok produk cabang.
                            </p>
                        </div>

                        <span class="text-3xl font-black text-emerald-700">
                            →
                        </span>
                    </a>

                    <a href="{{ route('warehouse.incoming-goods.index') }}"
                        class="flex items-center justify-between gap-4 p-5 rounded-2xl bg-emerald-50 border border-emerald-100 hover:bg-emerald-100 transition">
                        <div>
                            <p class="font-black text-emerald-800">
                                Barang Masuk
                            </p>

                            <p class="text-sm text-emerald-700 mt-1">
                                Tambah dan kelola barang masuk.
                            </p>
                        </div>

                        <span class="text-3xl font-black text-emerald-700">
                            →
                        </span>
                    </a>

                </div>
            </div>

            {{-- Tabel --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                {{-- Barang Masuk Terbaru --}}
                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200 flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-black text-slate-900">
                                Barang Masuk Terbaru
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                5 aktivitas barang masuk terakhir.
                            </p>
                        </div>

                        <a href="{{ route('warehouse.incoming-goods.index') }}"
                            class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Produk</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase text-center">Jumlah</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Tanggal</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @forelse ($recentIncomingGoods as $item)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-5">
                                            <p class="font-black text-slate-900">
                                                {{ $item->product->nama ?? '-' }}
                                            </p>

                                            <p class="text-xs text-slate-400 mt-1">
                                                Supplier: {{ $item->product->supplier->nama ?? '-' }}
                                            </p>
                                        </td>

                                        <td class="px-6 py-5 text-center">
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black">
                                                +{{ $item->jumlah }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-5 text-sm text-slate-600">
                                            {{ \Carbon\Carbon::parse($item->tanggal_masuk)->translatedFormat('d F Y H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-16 text-center text-slate-500">
                                            Belum ada barang masuk terbaru.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Produk Perlu Restock --}}
                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200 flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-black text-slate-900">
                                Produk Perlu Restock
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                Produk dengan stok kurang dari 30.
                            </p>
                        </div>

                        <a href="{{ route('warehouse.stocks.index') }}"
                            class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Produk</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase text-center">Stok</th>
                                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase text-right">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @forelse ($lowStocks as $stock)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-5">
                                            <p class="font-black text-slate-900">
                                                {{ $stock->product->nama ?? '-' }}
                                            </p>

                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ $stock->product->kode ?? '-' }}
                                            </p>
                                        </td>

                                        <td class="px-6 py-5 text-center font-black text-slate-900">
                                            {{ $stock->jumlah_stok }}
                                        </td>

                                        <td class="px-6 py-5 text-right">
                                            @if ($stock->jumlah_stok == 0)
                                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-black">
                                                    Habis
                                                </span>
                                            @else
                                                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-black">
                                                    Menipis
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-16 text-center text-slate-500">
                                            Semua stok masih aman.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
