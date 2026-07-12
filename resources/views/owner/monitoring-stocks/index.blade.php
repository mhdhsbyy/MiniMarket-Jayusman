<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Monitoring Stok
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Persediaan Barang
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Pantau stok barang dari seluruh cabang minimarket.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('owner.monitoring-stocks.excel', request()->query()) }}"
                        class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black text-sm hover:bg-emerald-800 transition">
                        Export Excel
                    </a>

                    <a href="{{ route('owner.monitoring-stocks.pdf', request()->query()) }}" target="_blank"
                        class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black text-sm hover:bg-emerald-800 transition">
                        Cetak Laporan PDF
                    </a>
                </div>
            </div>

            {{-- Statistic --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
                    <h2 class="text-4xl font-black text-red-700 mt-3">
                        {{ $produkHabis }}
                    </h2>
                </div>
            </div>

            {{-- Chart --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Total Stok per Cabang
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Grafik jumlah stok barang berdasarkan cabang.
                        </p>
                    </div>

                    <form method="GET" id="chartFilterForm"
                        class="grid grid-cols-1 md:grid-cols-3 gap-3 w-full xl:w-auto">

                        <select name="branch_id" onchange="document.getElementById('chartFilterForm').submit()"
                            class="w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Cabang</option>

                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->nama }}
                                </option>
                            @endforeach
                        </select>

                        <select name="category_id" onchange="document.getElementById('chartFilterForm').submit()"
                            class="w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Kategori</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama }}
                                </option>
                            @endforeach
                        </select>

                        <a href="{{ route('owner.monitoring-stocks.index') }}"
                            class="flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                            Reset
                        </a>
                    </form>
                </div>

                <div class="p-6">
                    <div class="h-[360px]">
                        <canvas id="branchStockChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Stok Rendah --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">
                        Stok Paling Rendah
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        5 produk dengan jumlah stok paling sedikit.
                    </p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($stokMenipisList as $index => $stock)
                        <div class="flex items-center justify-between gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-700 flex items-center justify-center font-black">
                                    {{ $index + 1 }}
                                </div>

                                <div class="min-w-0">
                                    <p class="font-black text-slate-900 truncate">
                                        {{ $stock->product->nama ?? '-' }}
                                    </p>

                                    <p class="text-sm text-slate-500 truncate">
                                        {{ $stock->branch->nama ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-black">
                                {{ $stock->jumlah_stok }}
                            </span>
                        </div>
                    @empty
                        <div class="md:col-span-2 lg:col-span-3 py-10 text-center text-slate-500">
                            Belum ada data stok.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Table --}}
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

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <input type="text" id="searchInput" placeholder="Cari stok..." autocomplete="off"
                            class="w-full md:w-72 rounded-2xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">

                        <button type="button" id="resetSearch"
                            class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                            Reset
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">No</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Produk</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kategori</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Cabang</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Stok</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($stocks as $stock)
                                @php
                                    $namaProduk = $stock->product->nama ?? '-';
                                    $kodeProduk = $stock->product->kode ?? '-';
                                    $namaKategori = $stock->product->category->nama ?? '-';
                                    $namaCabang = $stock->branch->nama ?? '-';
                                    $kotaCabang = $stock->branch->kota ?? '-';
                                    $nomor = $loop->iteration + ($stocks->currentPage() - 1) * $stocks->perPage();

                                    if ($stock->jumlah_stok == 0) {
                                        $statusLabel = 'Habis';
                                    } elseif ($stock->jumlah_stok < 30) {
                                        $statusLabel = 'Menipis';
                                    } else {
                                        $statusLabel = 'Aman';
                                    }
                                @endphp

                                <tr class="stock-row hover:bg-slate-50 transition"
                                    data-search="{{ strtolower($namaProduk . ' ' . $kodeProduk . ' ' . $namaKategori . ' ' . $namaCabang . ' ' . $kotaCabang . ' ' . $stock->jumlah_stok . ' ' . $statusLabel) }}">
                                    <td class="px-6 py-5 text-sm text-slate-500">
                                        {{ $nomor }}
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="font-black text-slate-900">
                                            {{ $namaProduk }}
                                        </p>

                                        <p class="text-sm text-slate-500">
                                            Kode: {{ $kodeProduk }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 text-sm font-bold text-slate-700">
                                        {{ $namaKategori }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="font-black text-slate-900">
                                            {{ $namaCabang }}
                                        </p>

                                        <p class="text-sm text-slate-500">
                                            {{ $kotaCabang }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 font-black text-slate-900">
                                        {{ number_format($stock->jumlah_stok, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5">
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
                                        Tidak ada data stok ditemukan.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="emptySearchRow" class="hidden">
                                <td colspan="5" class="px-6 py-16 text-center text-slate-500">
                                    Data stok tidak ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-200">
                    {{ $stocks->links() }}
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('branchStockChart');

            if (ctx) {
                const chartData = @json($dataStokCabang);
                const maxStok = Math.max(...chartData, 0);
                const suggestedMax = maxStok + (maxStok * 0.25);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($labelsCabang),
                        datasets: [{
                            label: 'Total Stok',
                            data: chartData,
                            borderWidth: 1,
                            borderRadius: 12,
                            maxBarThickness: 56
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return new Intl.NumberFormat('id-ID').format(context.raw) + ' stok';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: suggestedMax,
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            const searchInput = document.getElementById('searchInput');
            const resetSearch = document.getElementById('resetSearch');
            const rows = document.querySelectorAll('.stock-row');
            const emptySearchRow = document.getElementById('emptySearchRow');

            function filterRows() {
                const keyword = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                rows.forEach(function(row) {
                    const data = row.dataset.search || '';

                    if (data.includes(keyword)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (emptySearchRow) {
                    emptySearchRow.classList.toggle('hidden', visibleCount > 0);
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterRows);

                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                    }
                });
            }

            if (resetSearch) {
                resetSearch.addEventListener('click', function() {
                    searchInput.value = '';
                    filterRows();
                    searchInput.focus();
                });
            }
        });
    </script>
</x-app-layout>
