<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8">
                <p class="text-sm font-black text-emerald-700 uppercase tracking-widest">
                    Monitoring Stok
                </p>

                <h1 class="text-4xl font-black text-slate-900 mt-3">
                    Persediaan Barang
                </h1>

                <p class="text-slate-500 mt-2">
                    Pantau stok barang dari seluruh cabang minimarket.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Produk</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $totalProdukStok }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Stok</p>
                    <h2 class="text-4xl font-black text-emerald-700 mt-3">
                        {{ number_format($totalStokBarang, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Stok Menipis</p>
                    <h2 class="text-4xl font-black text-amber-600 mt-3">
                        {{ $produkMenipis }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Stok Habis</p>
                    <h2 class="text-4xl font-black text-red-600 mt-3">
                        {{ $produkHabis }}
                    </h2>
                </div>
            </div>

            <form id="filterForm" method="GET" action="{{ route('owner.monitoring-stocks.index') }}"
                class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm mb-6">

                <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                    <div class="flex-1">
                        <label class="text-sm font-bold text-slate-600">Cabang</label>
                        <select name="branch_id" id="branchFilter"
                            class="mt-2 w-full rounded-2xl border-slate-200 text-sm">
                            <option value="">Semua Cabang</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1">
                        <label class="text-sm font-bold text-slate-600">Kategori</label>
                        <select name="category_id" id="categoryFilter"
                            class="mt-2 w-full rounded-2xl border-slate-200 text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <a href="{{ route('owner.monitoring-stocks.index') }}"
                        class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-black text-sm text-center">
                        Reset
                    </a>
                </div>
            </form>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-black text-slate-900">
                            Total Stok per Cabang
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Semakin tinggi batang, semakin banyak stok tersedia di cabang tersebut.
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="h-[320px]">
                            <canvas id="branchStockChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-black text-slate-900">
                            Stok Paling Rendah
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            5 produk dengan stok paling sedikit.
                        </p>
                    </div>

                    <div class="p-6 space-y-4">
                        @forelse ($stokMenipisList as $index => $stock)
                            <div class="flex items-center justify-between gap-4 pb-4 border-b border-slate-100 last:border-b-0 last:pb-0">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-red-100 text-red-700 flex items-center justify-center font-black">
                                            {{ $index + 1 }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-black text-slate-900 truncate">
                                                {{ $stock->product->nama ?? '-' }}
                                            </p>
                                            <p class="text-sm text-slate-500">
                                                {{ $stock->branch->nama ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-black">
                                    {{ $stock->jumlah_stok }}
                                </span>
                            </div>
                        @empty
                            <p class="text-slate-500 text-sm">Belum ada data stok.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Daftar Stok Barang
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Cari stok berdasarkan produk, kategori, cabang, atau jumlah stok.
                        </p>
                    </div>

                    <input type="text" id="searchStock"
                        placeholder="Cari stok..."
                        class="w-full md:w-72 rounded-2xl border-slate-200 text-sm">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[950px]">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Produk</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Cabang</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase">Stok</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($stocks as $stock)
                                <tr class="stock-row hover:bg-slate-50 transition">
                                    <td class="px-6 py-5">
                                        <p class="font-black text-slate-900">
                                            {{ $stock->product->nama ?? '-' }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            Kode: {{ $stock->product->kode ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 text-sm font-bold text-slate-700">
                                        {{ $stock->product->category->nama ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="font-black text-slate-900">
                                            {{ $stock->branch->nama ?? '-' }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            {{ $stock->branch->kota ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 text-right font-black text-slate-900">
                                        {{ number_format($stock->jumlah_stok, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5 text-right">
                                        @if ($stock->jumlah_stok <= 0)
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-black">
                                                Habis
                                            </span>
                                        @elseif ($stock->jumlah_stok <= 30)
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
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        Tidak ada data stok ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div id="noSearchResult" class="hidden px-6 py-12 text-center text-slate-500">
                        Data stok tidak ditemukan.
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100">
                    {{ $stocks->links() }}
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const labelsCabang = @json($labelsCabang);
        const dataStokCabang = @json($dataStokCabang);

        new Chart(document.getElementById('branchStockChart'), {
            type: 'bar',
            data: {
                labels: labelsCabang,
                datasets: [{
                    label: 'Total Stok',
                    data: dataStokCabang,
                    borderWidth: 1,
                    borderRadius: 12,
                    borderSkipped: false,
                    maxBarThickness: 56
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('id-ID').format(context.raw) + ' stok';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { weight: 'bold' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        const filterForm = document.getElementById('filterForm');

        document.getElementById('branchFilter').addEventListener('change', function () {
            filterForm.submit();
        });

        document.getElementById('categoryFilter').addEventListener('change', function () {
            filterForm.submit();
        });

        const searchInput = document.getElementById('searchStock');
        const stockRows = document.querySelectorAll('.stock-row');
        const noSearchResult = document.getElementById('noSearchResult');

        searchInput.addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase();
            let visibleCount = 0;

            stockRows.forEach(function (row) {
                const text = row.innerText.toLowerCase();

                if (text.includes(keyword)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (stockRows.length > 0) {
                noSearchResult.classList.toggle('hidden', visibleCount !== 0);
            }
        });
    </script>
</x-app-layout>
