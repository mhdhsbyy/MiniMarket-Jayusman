<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Stok Cabang
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-2">
                        Monitoring Stok
                    </h1>

                    <p class="text-slate-500 mt-2">
                        Pantau stok produk pada cabang yang Anda kelola.
                    </p>
                </div>

                <a href="{{ route('manager.stocks.pdf', request()->query()) }}"
                    target="_blank"
                    class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black text-sm hover:bg-emerald-800 transition">
                    Cetak Laporan PDF
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-bold text-slate-500 uppercase">Total Produk</p>
                    <h2 class="text-3xl font-black text-slate-900 mt-3">{{ $totalProduk }}</h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-bold text-slate-500 uppercase">Total Stok</p>
                    <h2 class="text-3xl font-black text-emerald-700 mt-3">{{ $totalStok }}</h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-bold text-slate-500 uppercase">Stok Menipis</p>
                    <h2 class="text-3xl font-black text-amber-700 mt-3">{{ $stokMenipis }}</h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-bold text-slate-500 uppercase">Stok Habis</p>
                    <h2 class="text-3xl font-black text-red-700 mt-3">{{ $stokHabis }}</h2>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-bold text-slate-600">Kategori</label>
                        <select name="category_id"
                            class="filter-input mt-2 w-full rounded-2xl border-slate-200 focus:border-emerald-600 focus:ring-emerald-600">
                            <option value="">Semua Kategori</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-600">Status Stok</label>
                        <select name="status_stok"
                            class="filter-input mt-2 w-full rounded-2xl border-slate-200 focus:border-emerald-600 focus:ring-emerald-600">
                            <option value="">Semua Status</option>
                            <option value="normal" {{ request('status_stok') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="menipis" {{ request('status_stok') == 'menipis' ? 'selected' : '' }}>Menipis</option>
                            <option value="habis" {{ request('status_stok') == 'habis' ? 'selected' : '' }}>Habis</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <a href="{{ route('manager.stocks.index') }}"
                            class="w-full text-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Grafik Stok Terendah
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Menampilkan 10 produk dengan jumlah stok paling rendah.
                        </p>
                    </div>
                </div>

                <div class="h-80">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">
                        Daftar Stok Produk
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Data stok produk terbaru berdasarkan filter.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Produk</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Stok</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Satuan</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($stocks as $stock)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-5 text-sm font-bold text-slate-900">
                                        {{ $stock->product->nama ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $stock->product->category->nama ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5 text-sm font-black text-slate-900">
                                        {{ $stock->jumlah_stok }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $stock->product->satuan ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($stock->jumlah_stok == 0)
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-black">
                                                Habis
                                            </span>
                                        @elseif ($stock->jumlah_stok <= 10)
                                            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-black">
                                                Menipis
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black">
                                                Normal
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada data stok.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6">
                    {{ $stocks->links() }}
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.querySelectorAll('.filter-input').forEach(function(input) {
            input.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        const ctx = document.getElementById('stockChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Jumlah Stok',
                    data: @json($chartData),
                    borderWidth: 2,
                    borderRadius: 10
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>
