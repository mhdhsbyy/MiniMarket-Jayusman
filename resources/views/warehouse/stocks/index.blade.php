<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Warehouse Panel
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Data Stok
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Pantau ketersediaan stok produk pada cabang Anda.
                    </p>
                </div>

                <a href="{{ route('warehouse.incoming-goods.index') }}"
                    class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black hover:bg-emerald-800 transition shadow-lg shadow-emerald-900/20 text-center">
                    Barang Masuk
                </a>
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
                        Stok Aman
                    </p>

                    <h2 class="text-4xl font-black text-emerald-700 mt-3">
                        {{ $stokAman }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Stok 30 ke atas
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

            {{-- Table --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">

                <div class="p-6 border-b border-slate-200 space-y-5">

                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Daftar Stok Produk
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Data stok hanya menampilkan produk dari cabang Anda.
                        </p>
                    </div>

                    {{-- Filter Realtime Server Side --}}
                    <form id="stockFilterForm" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="md:col-span-2">
                            <input type="text"
                                name="search"
                                id="stockSearch"
                                value="{{ request('search') }}"
                                placeholder="Cari nama produk, kode, kategori, atau supplier..."
                                class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-600 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <select name="status"
                            id="stockStatus"
                            class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-600 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="semua" {{ request('status', 'semua') === 'semua' ? 'selected' : '' }}>
                                Semua Status
                            </option>

                            <option value="aman" {{ request('status') === 'aman' ? 'selected' : '' }}>
                                Aman
                            </option>

                            <option value="menipis" {{ request('status') === 'menipis' ? 'selected' : '' }}>
                                Menipis
                            </option>

                            <option value="habis" {{ request('status') === 'habis' ? 'selected' : '' }}>
                                Habis
                            </option>
                        </select>
                    </form>

                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase w-12">
                                    No
                                </th>

                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">
                                    Produk
                                </th>

                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">
                                    Kategori
                                </th>

                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">
                                    Supplier
                                </th>

                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase text-center">
                                    Stok
                                </th>

                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase text-right">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($stocks as $stock)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-sm font-black text-slate-400 text-center">
                                        {{ ($stocks->currentPage() - 1) * $stocks->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="font-black text-slate-900">
                                            {{ $stock->product->nama ?? '-' }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-1">
                                            {{ $stock->product->kode ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600 font-bold">
                                        {{ $stock->product->category->nama ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600 font-bold">
                                        {{ $stock->product->supplier->nama ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5 text-center">
                                        <span class="font-black text-slate-900">
                                            {{ $stock->jumlah_stok }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5 text-right">
                                        @if ($stock->jumlah_stok == 0)
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-black">
                                                Habis
                                            </span>
                                        @elseif ($stock->jumlah_stok < 30)
                                            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-black">
                                                Menipis
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black">
                                                Aman
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-slate-500">
                                        Data stok tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($stocks->hasPages())
                    <div class="px-6 py-5 border-t border-slate-200">
                        {{ $stocks->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>

    <script>
        const stockFilterForm = document.getElementById('stockFilterForm');
        const stockSearch = document.getElementById('stockSearch');
        const stockStatus = document.getElementById('stockStatus');

        let stockTypingTimer;

        stockSearch?.addEventListener('input', function () {
            clearTimeout(stockTypingTimer);

            stockTypingTimer = setTimeout(() => {
                stockFilterForm.submit();
            }, 400);
        });

        stockStatus?.addEventListener('change', function () {
            stockFilterForm.submit();
        });
    </script>
</x-app-layout>
