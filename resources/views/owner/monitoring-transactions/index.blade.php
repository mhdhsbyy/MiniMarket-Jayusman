<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Monitoring Transaksi
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Riwayat Transaksi
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Pantau pendapatan dan transaksi dari seluruh cabang.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('owner.monitoring-transactions.excel', request()->query()) }}"
                        class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black text-sm hover:bg-emerald-800 transition">
                        Export Excel
                    </a>

                    <a href="{{ route('owner.monitoring-transactions.pdf', request()->query()) }}" target="_blank"
                        class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black text-sm hover:bg-emerald-800 transition">
                        Cetak Laporan PDF
                    </a>
                </div>
            </div>

            {{-- Statistic --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Transaksi</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $totalTransaksi }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Pendapatan</p>
                    <h2 class="text-3xl font-black text-emerald-700 mt-3">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Cabang Terbaik</p>

                    @if ($cabangTerbaik)
                        <h2 class="text-2xl font-black text-slate-900 mt-3">
                            {{ $cabangTerbaik->nama }}
                        </h2>

                        <p class="text-sm font-bold text-emerald-700 mt-1">
                            Rp {{ number_format($cabangTerbaik->total_pendapatan, 0, ',', '.') }}
                        </p>
                    @else
                        <h2 class="text-2xl font-black text-slate-900 mt-3">
                            -
                        </h2>
                    @endif
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Cabang</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $branches->count() }}
                    </h2>
                </div>
            </div>

            {{-- Chart --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Pendapatan per Cabang
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Grafik pendapatan transaksi berdasarkan cabang.
                        </p>
                    </div>

                    <form method="GET" id="chartFilterForm"
                        class="grid grid-cols-1 md:grid-cols-5 gap-3 w-full xl:w-auto">

                        <select name="periode" onchange="document.getElementById('chartFilterForm').submit()"
                            class="w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="semua" {{ request('periode', 'semua') == 'semua' ? 'selected' : '' }}>
                                Semua Periode
                            </option>
                            <option value="harian" {{ request('periode') == 'harian' ? 'selected' : '' }}>Per Hari</option>
                            <option value="mingguan" {{ request('periode') == 'mingguan' ? 'selected' : '' }}>Per Minggu</option>
                            <option value="bulanan" {{ request('periode') == 'bulanan' ? 'selected' : '' }}>Per Bulan</option>
                            <option value="tahunan" {{ request('periode') == 'tahunan' ? 'selected' : '' }}>Per Tahun</option>
                        </select>

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

                        <input type="text" name="start_date" id="start_date" value="{{ request('start_date') }}"
                            placeholder="dd/mm/yyyy"
                            class="datepicker w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">

                        <input type="text" name="end_date" id="end_date" value="{{ request('end_date') }}"
                            placeholder="dd/mm/yyyy"
                            class="datepicker w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">

                        <a href="{{ route('owner.monitoring-transactions.index') }}"
                            class="flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                            Reset
                        </a>
                    </form>
                </div>

                <div class="p-6">
                    <div class="h-[360px]">
                        <canvas id="branchIncomeChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Ranking Cabang --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">
                        Ranking Cabang
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Urutan cabang berdasarkan pendapatan tertinggi.
                    </p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($labelsCabang as $index => $label)
                        <div class="flex items-center justify-between gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black">
                                    {{ $index + 1 }}
                                </div>

                                <div>
                                    <p class="font-black text-slate-900">
                                        {{ $label }}
                                    </p>

                                    <p class="text-sm font-bold text-emerald-700">
                                        Rp {{ number_format($dataPendapatanCabang[$index] ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 lg:col-span-3 py-10 text-center text-slate-500">
                            Belum ada data cabang.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Daftar Transaksi
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Cari transaksi berdasarkan cabang, kasir, tanggal, total, atau kode.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <input type="text" id="searchInput" placeholder="Cari transaksi..." autocomplete="off"
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
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kode</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Cabang</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kasir</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Total</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($transactions as $transaction)
                                @php
                                    $kodeTransaksi = 'TRX-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);
                                    $namaCabang = $transaction->branch->nama ?? '-';
                                    $kotaCabang = $transaction->branch->kota ?? '-';
                                    $namaKasir = trim(($transaction->cashier->first_name ?? '') . ' ' . ($transaction->cashier->last_name ?? ''));
                                    $tanggalTransaksi = \Carbon\Carbon::parse($transaction->tanggal_transaksi)->translatedFormat('d F Y H:i');
                                    $nomor = $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage();
                                @endphp

                                <tr class="transaction-row hover:bg-slate-50 transition"
                                    data-search="{{ strtolower($kodeTransaksi . ' ' . $tanggalTransaksi . ' ' . $namaCabang . ' ' . $kotaCabang . ' ' . $namaKasir . ' ' . $transaction->total_bayar) }}">
                                    <td class="px-6 py-5 text-sm text-slate-500">
                                        {{ $nomor }}
                                    </td>
                                    <td class="px-6 py-5 font-black text-slate-900">
                                        {{ $kodeTransaksi }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $tanggalTransaksi }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="font-black text-slate-900">
                                            {{ $namaCabang }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            {{ $kotaCabang }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 font-bold text-slate-900">
                                        {{ $namaKasir ?: '-' }}
                                    </td>

                                    <td class="px-6 py-5 font-black text-emerald-700">
                                        Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5 text-right">
                                        <a href="{{ route('owner.monitoring-transactions.show', $transaction->id) }}"
                                            class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center text-slate-500">
                                        Tidak ada transaksi ditemukan.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="emptySearchRow" class="hidden">
                                <td colspan="7" class="px-6 py-16 text-center text-slate-500">
                                    Data transaksi tidak ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-200">
                    {{ $transactions->links() }}
                </div>
            </div>

        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('branchIncomeChart');

            if (ctx) {
                const chartData = @json($dataPendapatanCabang);
                const maxPendapatan = Math.max(...chartData, 0);
                const suggestedMax = maxPendapatan + (maxPendapatan * 0.25);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($labelsCabang),
                        datasets: [{
                            label: 'Pendapatan',
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
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: suggestedMax,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
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

            flatpickr('#start_date, #end_date', {
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
            const rows = document.querySelectorAll('.transaction-row');
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
