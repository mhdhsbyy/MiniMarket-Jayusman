<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8">
                <p class="text-sm font-black text-emerald-700 uppercase tracking-widest">
                    Monitoring Transaksi
                </p>

                <h1 class="text-4xl font-black text-slate-900 mt-3">
                    Riwayat Transaksi
                </h1>

                <p class="text-slate-500 mt-2">
                    Pantau pendapatan dan transaksi dari seluruh cabang.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
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
                        <p class="text-sm text-slate-500 mt-1">
                            Rp {{ number_format($cabangTerbaik->total_pendapatan, 0, ',', '.') }}
                        </p>
                    @else
                        <h2 class="text-2xl font-black text-slate-900 mt-3">-</h2>
                    @endif
                </div>
            </div>

            <form id="filterForm" method="GET" action="{{ route('owner.monitoring-transactions.index') }}"
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
                        <label class="text-sm font-bold text-slate-600">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="startDateFilter"
                            value="{{ request('start_date') }}"
                            class="mt-2 w-full rounded-2xl border-slate-200 text-sm">
                    </div>

                    <div class="flex-1">
                        <label class="text-sm font-bold text-slate-600">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="endDateFilter"
                            value="{{ request('end_date') }}"
                            class="mt-2 w-full rounded-2xl border-slate-200 text-sm">
                    </div>

                    <div>
                        <a href="{{ route('owner.monitoring-transactions.index') }}"
                            class="inline-flex px-5 py-3 rounded-2xl bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-700/20 hover:bg-emerald-800 font-black text-sm">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-black text-slate-900">
                            Pendapatan per Cabang
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Semakin tinggi batang, semakin besar pendapatan cabang tersebut.
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="h-[320px]">
                            <canvas id="branchIncomeChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-black text-slate-900">
                            Ranking Cabang
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Urutan pendapatan tertinggi.
                        </p>
                    </div>

                    <div class="p-6 space-y-4">
                        @forelse ($labelsCabang as $index => $label)
                            <div
                                class="flex items-center justify-between gap-4 pb-4 border-b border-slate-100 last:border-b-0 last:pb-0">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black">
                                        {{ $index + 1 }}
                                    </div>

                                    <p class="font-black text-slate-900">
                                        {{ $label }}
                                    </p>
                                </div>

                                <p class="text-sm font-black text-emerald-700">
                                    Rp {{ number_format($dataPendapatanCabang[$index] ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                        @empty
                            <div class="py-10 text-center text-slate-500">
                                Belum ada data cabang.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div
                    class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Daftar Transaksi
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Data transaksi terbaru sesuai filter yang dipilih.
                        </p>
                    </div>

                    <div>
                        <input type="text" id="searchTransaction"
                            placeholder="Cari cabang / kasir / total..."
                            class="w-full sm:w-72 rounded-2xl border-slate-200 text-sm">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px]">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Cabang</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kasir</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase">Total</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="transactionTableBody" class="divide-y divide-slate-100">
                            @forelse ($transactions as $transaction)
                                <tr class="transaction-row hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y H:i') }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="font-black text-slate-900">
                                            {{ $transaction->branch->nama ?? '-' }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            {{ $transaction->branch->kota ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 text-sm font-bold text-slate-700">
                                        {{ $transaction->cashier->first_name ?? '-' }}
                                        {{ $transaction->cashier->last_name ?? '' }}
                                    </td>

                                    <td class="px-6 py-5 text-right font-black text-emerald-700">
                                        Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5 text-right">
                                        <a href="{{ route('owner.monitoring-transactions.show', $transaction->id) }}"
                                            class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-bold hover:bg-slate-200 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyTransactionRow">
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        Tidak ada transaksi ditemukan.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="noRealtimeResultRow" class="hidden">
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    Tidak ada data yang cocok dengan pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100">
                    {{ $transactions->links() }}
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const labelsCabang = @json($labelsCabang);
        const dataPendapatanCabang = @json($dataPendapatanCabang);

        new Chart(document.getElementById('branchIncomeChart'), {
            type: 'bar',
            data: {
                labels: labelsCabang,
                datasets: [{
                    label: 'Pendapatan',
                    data: dataPendapatanCabang,
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
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
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
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });

        const filterForm = document.getElementById('filterForm');
        const branchFilter = document.getElementById('branchFilter');
        const startDateFilter = document.getElementById('startDateFilter');
        const endDateFilter = document.getElementById('endDateFilter');

        branchFilter.addEventListener('change', function () {
            filterForm.submit();
        });

        startDateFilter.addEventListener('change', function () {
            filterForm.submit();
        });

        endDateFilter.addEventListener('change', function () {
            filterForm.submit();
        });

        const searchInput = document.getElementById('searchTransaction');
        const transactionRows = document.querySelectorAll('.transaction-row');
        const noRealtimeResultRow = document.getElementById('noRealtimeResultRow');

        searchInput.addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase();
            let visibleCount = 0;

            transactionRows.forEach(function (row) {
                const text = row.innerText.toLowerCase();

                if (text.includes(keyword)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noRealtimeResultRow) {
                noRealtimeResultRow.classList.toggle('hidden', visibleCount !== 0);
            }
        });
    </script>
</x-app-layout>
