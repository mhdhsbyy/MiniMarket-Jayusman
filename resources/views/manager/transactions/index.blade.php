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
                        Transaksi Cabang
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Pantau transaksi yang terjadi pada cabang
                        <span class="font-bold text-slate-700">
                            {{ Auth::user()->branch->nama ?? 'Cabang' }}
                        </span>.
                    </p>
                </div>

                <a href="{{ route('manager.transactions.pdf', request()->query()) }}" target="_blank"
                    class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black text-sm hover:bg-emerald-800 transition">
                    Cetak Laporan PDF
                </a>
            </div>

            {{-- Statistic --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Transaksi</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">{{ $totalTransaksi }}</h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Transaksi Selesai</p>
                    <h2 class="text-4xl font-black text-emerald-700 mt-3">{{ $transaksiSelesai }}</h2>
                </div>


                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Transaksi Batal</p>
                    <h2 class="text-4xl font-black text-red-700 mt-3">{{ $transaksiBatal }}</h2>
                </div>
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Pendapatan</p>
                    <h2 class="text-3xl font-black text-emerald-700 mt-3">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            {{-- Chart --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Grafik Pendapatan
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Grafik pendapatan berdasarkan transaksi selesai.
                        </p>
                    </div>

                    <form method="GET" id="chartFilterForm"
                        class="grid grid-cols-1 md:grid-cols-5 gap-3 w-full xl:w-auto">

                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                            onchange="document.getElementById('chartFilterForm').submit()"
                            class="w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">

                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                            onchange="document.getElementById('chartFilterForm').submit()"
                            class="w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">

                        <select name="status" onchange="document.getElementById('chartFilterForm').submit()"
                            class="w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Status</option>
                            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Batal</option>
                        </select>

                        <select name="periode" onchange="document.getElementById('chartFilterForm').submit()"
                            class="w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="harian" {{ request('periode', 'harian') == 'harian' ? 'selected' : '' }}>Per Hari</option>
                            <option value="mingguan" {{ request('periode') == 'mingguan' ? 'selected' : '' }}>Per Minggu</option>
                            <option value="bulanan" {{ request('periode') == 'bulanan' ? 'selected' : '' }}>Per Bulan</option>
                            <option value="tahunan" {{ request('periode') == 'tahunan' ? 'selected' : '' }}>Per Tahun</option>
                        </select>

                        <a href="{{ route('manager.transactions.index') }}"
                            class="flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                            Reset
                        </a>
                    </form>
                </div>

                <div class="p-6">
                    <div class="h-[360px]">
                        <canvas id="transactionChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Daftar Transaksi</h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Cari transaksi berdasarkan kode, kasir, tanggal, total, atau status.
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
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kode</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kasir</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Total</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Dibayar</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kembalian</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($transactions as $transaction)
                                @php
                                    $kodeTransaksi = 'TRX-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);
                                    $namaKasir = trim(($transaction->cashier->first_name ?? '') . ' ' . ($transaction->cashier->last_name ?? ''));
                                    $tanggalTransaksi = \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y H:i');
                                    $statusLabel = $transaction->status == 'success' ? 'Selesai' : 'Batal';
                                @endphp

                                <tr class="transaction-row hover:bg-slate-50 transition"
                                    data-search="{{ strtolower($kodeTransaksi . ' ' . $namaKasir . ' ' . $tanggalTransaksi . ' ' . $transaction->total_bayar . ' ' . $transaction->uang_dibayar . ' ' . $transaction->kembalian . ' ' . $statusLabel) }}">
                                    <td class="px-6 py-5 font-black text-slate-900">{{ $kodeTransaksi }}</td>
                                    <td class="px-6 py-5 font-bold text-slate-900">{{ $namaKasir ?: '-' }}</td>
                                    <td class="px-6 py-5 text-sm text-slate-600">{{ $tanggalTransaksi }}</td>
                                    <td class="px-6 py-5 font-black text-emerald-700">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
                                    <td class="px-6 py-5 text-sm text-slate-600">Rp {{ number_format($transaction->uang_dibayar, 0, ',', '.') }}</td>
                                    <td class="px-6 py-5 text-sm text-slate-600">Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
                                    <td class="px-6 py-5">
                                        @if ($transaction->status == 'success')
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black">Selesai</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-black">Batal</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center text-slate-500">
                                        Tidak ada data transaksi.
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('transactionChart');

            if (ctx) {
                const chartData = @json($chartData);
                const maxPendapatan = Math.max(...chartData, 0);
                const suggestedMax = maxPendapatan + (maxPendapatan * 0.25);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Pendapatan',
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
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

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
