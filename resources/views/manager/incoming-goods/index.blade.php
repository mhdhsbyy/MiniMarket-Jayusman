<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Monitoring Barang Masuk
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Barang Masuk
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Pantau barang masuk pada cabang
                        <span class="font-bold text-slate-700">
                            {{ Auth::user()->branch->nama ?? 'Cabang' }}
                        </span>.
                    </p>
                </div>

                <a href="{{ route('manager.incoming-goods.pdf', request()->query()) }}" target="_blank"
                    class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black text-sm hover:bg-emerald-800 transition">
                    Cetak Laporan PDF
                </a>
            </div>

            {{-- Statistic --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Barang Masuk</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $totalBarangMasuk }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Jumlah Masuk</p>
                    <h2 class="text-4xl font-black text-emerald-700 mt-3">
                        {{ number_format($totalJumlahMasuk, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Biaya</p>
                    <h2 class="text-3xl font-black text-amber-700 mt-3">
                        Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            {{-- Chart --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Grafik Barang Masuk
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Jumlah barang masuk per tanggal.
                        </p>
                    </div>

                    <form method="GET" id="chartFilterForm"
                        class="flex flex-wrap items-center justify-end gap-3">

                        <input type="text" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                            placeholder="dd/mm/yyyy"
                            class="datepicker w-40 rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">

                        <input type="text" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                            placeholder="dd/mm/yyyy"
                            class="datepicker w-40 rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">

                        <a href="{{ route('manager.incoming-goods.index') }}"
                            class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                            Reset
                        </a>
                    </form>
                </div>

                <div class="p-6">
                    <div class="h-[360px]">
                        <canvas id="incomingChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Daftar Barang Masuk
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Cari barang masuk berdasarkan produk, supplier, atau petugas.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <input type="text" id="searchInput" placeholder="Cari barang masuk..." autocomplete="off"
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
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase w-12">No</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Produk</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Supplier</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Jumlah</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Harga Beli</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Total Biaya</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Petugas</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($incomingGoods as $incomingGood)
                                @php
                                    $namaProduk = $incomingGood->product->nama ?? '-';
                                    $kodeProduk = $incomingGood->product->kode ?? '-';
                                    $namaSupplier = $incomingGood->product->supplier->nama ?? '-';
                                    $namaPetugas = $incomingGood->user->first_name ?? '-';
                                    $totalBiayaItem = $incomingGood->harga_beli * $incomingGood->jumlah;
                                @endphp

                                <tr class="incoming-row hover:bg-slate-50 transition"
                                    data-search="{{ strtolower($namaProduk . ' ' . $kodeProduk . ' ' . $namaSupplier . ' ' . $namaPetugas) }}">
                                    <td class="px-6 py-5 text-sm font-black text-slate-400 text-center">
                                        {{ ($incomingGoods->currentPage() - 1) * $incomingGoods->perPage() + $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-5 text-sm font-bold text-slate-700">
                                        {{ \Carbon\Carbon::parse($incomingGood->tanggal_masuk)->translatedFormat('d F Y H:i') }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="font-black text-slate-900">
                                            {{ $namaProduk }}
                                        </p>

                                        <p class="text-sm text-slate-500">
                                            Kode: {{ $kodeProduk }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $namaSupplier }}
                                    </td>

                                    <td class="px-6 py-5 font-black text-slate-900">
                                        {{ $incomingGood->jumlah }}
                                    </td>

                                    <td class="px-6 py-5 font-bold text-slate-700">
                                        Rp {{ number_format($incomingGood->harga_beli, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5 font-black text-amber-700">
                                        Rp {{ number_format($totalBiayaItem, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $namaPetugas }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16 text-center text-slate-500">
                                        Tidak ada data barang masuk.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="emptySearchRow" class="hidden">
                                <td colspan="8" class="px-6 py-16 text-center text-slate-500">
                                    Data barang masuk tidak ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-200">
                    {{ $incomingGoods->links() }}
                </div>
            </div>

        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartCtx = document.getElementById('incomingChart');

            if (chartCtx) {
                const chartData = @json($chartData);
                const maxData = Math.max(...chartData, 0);
                const suggestedMax = maxData + (maxData * 0.25);

                new Chart(chartCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Jumlah Barang Masuk',
                            data: chartData,
                            borderWidth: 1,
                            borderRadius: 12
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
                                        return context.raw + ' item';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: suggestedMax,
                                ticks: {
                                    stepSize: 1
                                }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            flatpickr('#tanggal_mulai, #tanggal_selesai', {
                altInput: true,
                altFormat: 'd/m/Y',
                dateFormat: 'Y-m-d',
                locale: 'id',
                allowInput: true,
                onChange: function() {
                    document.getElementById('chartFilterForm').submit();
                }
            });

            const searchInput = document.getElementById('searchInput');
            const resetSearch = document.getElementById('resetSearch');
            const rows = document.querySelectorAll('.incoming-row');
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
