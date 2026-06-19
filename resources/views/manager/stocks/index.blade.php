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
                        Stok Cabang
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Pantau stok produk pada cabang
                        <span class="font-bold text-slate-700">
                            {{ Auth::user()->branch->nama ?? 'Cabang' }}
                        </span>.
                    </p>
                </div>

                <a href="{{ route('manager.stocks.pdf', request()->query()) }}" target="_blank"
                    class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black text-sm hover:bg-emerald-800 transition">
                    Cetak Laporan PDF
                </a>
            </div>

            {{-- Statistic --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Produk</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">{{ $totalProduk }}</h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Stok</p>
                    <h2 class="text-4xl font-black text-emerald-700 mt-3">{{ $totalStok }}</h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Stok Menipis</p>
                    <h2 class="text-4xl font-black text-amber-700 mt-3">{{ $stokMenipis }}</h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Stok Habis</p>
                    <h2 class="text-4xl font-black text-red-700 mt-3">{{ $stokHabis }}</h2>
                </div>
            </div>

            {{-- Chart --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Grafik Stok Terendah
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Menampilkan 10 produk dengan jumlah stok paling rendah.
                        </p>
                    </div>

                    <form method="GET" id="chartFilterForm"
                        class="grid grid-cols-1 md:grid-cols-3 gap-3 w-full xl:w-auto">

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

                        <select name="status_stok" onchange="document.getElementById('chartFilterForm').submit()"
                            class="w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Status</option>
                            <option value="aman" {{ request('status_stok') == 'aman' ? 'selected' : '' }}>Aman</option>
                            <option value="menipis" {{ request('status_stok') == 'menipis' ? 'selected' : '' }}>Menipis</option>
                            <option value="habis" {{ request('status_stok') == 'habis' ? 'selected' : '' }}>Habis</option>
                        </select>

                        <a href="{{ route('manager.stocks.index') }}"
                            class="flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                            Reset
                        </a>
                    </form>
                </div>

                <div class="p-6">
                    <div class="h-[360px]">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Daftar Stok Produk</h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Cari stok berdasarkan produk, kategori, jumlah stok, satuan, atau status.
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
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Produk</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kategori</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Stok</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Satuan</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($stocks as $stock)
                                @php
                                    $namaProduk = $stock->product->nama ?? '-';
                                    $namaKategori = $stock->product->category->nama ?? '-';
                                    $satuan = $stock->product->satuan ?? '-';

                                    if ($stock->jumlah_stok == 0) {
                                        $statusLabel = 'Habis';
                                    } elseif ($stock->jumlah_stok < 30) {
                                        $statusLabel = 'Menipis';
                                    } else {
                                        $statusLabel = 'Aman';
                                    }
                                @endphp

                                <tr class="stock-row hover:bg-slate-50 transition"
                                    data-search="{{ strtolower($namaProduk . ' ' . $namaKategori . ' ' . $stock->jumlah_stok . ' ' . $satuan . ' ' . $statusLabel) }}">
                                    <td class="px-6 py-5 font-black text-slate-900">
                                        {{ $namaProduk }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $namaKategori }}
                                    </td>

                                    <td class="px-6 py-5 font-black text-slate-900">
                                        {{ $stock->jumlah_stok }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $satuan }}
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
                                    <td colspan="5" class="px-6 py-16 text-center text-slate-500">
                                        Tidak ada data stok.
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
            const ctx = document.getElementById('stockChart');

            if (ctx) {
                const chartData = @json($chartData);
                const maxStok = Math.max(...chartData, 0);
                const suggestedMax = maxStok + (maxStok * 0.25);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Jumlah Stok',
                            data: chartData,
                            borderWidth: 1,
                            borderRadius: 12
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Stok: ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                suggestedMax: suggestedMax
                            },
                            y: {
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
                    if (e.key === 'Enter') e.preventDefault();
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
