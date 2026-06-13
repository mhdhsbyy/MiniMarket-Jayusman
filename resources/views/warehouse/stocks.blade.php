<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8">
                <h1 class="text-4xl font-black text-slate-900">
                    Stok Barang
                </h1>

                <p class="text-slate-500 mt-2">
                    Daftar stok barang pada cabang {{ Auth::user()->branch->nama ?? '-' }}.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Total Produk</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $stocks->count() }}
                    </h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Produk di cabang ini</p>
                </div>

                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Stok Aman</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $stocks->where('jumlah_stok', '>', 10)->count() }}
                    </h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Jumlah stok mencukupi</p>
                </div>

                <div class="bg-red-50 rounded-[1.7rem] p-6 border border-red-100 shadow-sm">
                    <p class="text-sm text-red-500">Stok Menipis</p>
                    <h2 class="text-4xl font-black text-red-600 mt-3">
                        {{ $stocks->where('jumlah_stok', '<=', 10)->count() }}
                    </h2>
                    <p class="text-xs font-semibold text-red-500 mt-3">Perlu segera restock</p>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Data Stok Barang
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Stok barang yang tersedia di cabang Anda.
                        </p>
                    </div>

                    <input type="text"
                        id="searchStock"
                        placeholder="Cari barang..."
                        class="w-full md:w-72 rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">No</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Barang</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Satuan</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Jumlah Stok</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($stocks as $stock)
                                <tr class="stock-row hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-sm text-slate-500">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="font-bold text-slate-900">
                                            {{ $stock->product->nama ?? '-' }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            Kode: {{ $stock->product->kode ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $stock->product->category->nama ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $stock->product->satuan ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5 text-sm font-black text-slate-900">
                                        {{ $stock->jumlah_stok }}
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($stock->jumlah_stok <= 0)
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                                Habis
                                            </span>
                                        @elseif ($stock->jumlah_stok <= 10)
                                            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                                Menipis
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                                Aman
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada data stok barang untuk cabang ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        const searchStock = document.getElementById('searchStock');
        const stockRows = document.querySelectorAll('.stock-row');

        searchStock.addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase();

            stockRows.forEach(function (row) {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(keyword) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>
