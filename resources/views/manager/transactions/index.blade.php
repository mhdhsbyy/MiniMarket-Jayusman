<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Transaksi Cabang
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-2">
                        Monitoring Transaksi
                    </h1>

                    <p class="text-slate-500 mt-2">
                        Pantau transaksi penjualan pada cabang yang Anda kelola.
                    </p>
                </div>

                <a href="{{ route('manager.transactions.pdf', request()->query()) }}" target="_blank"
                    class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black text-sm hover:bg-emerald-800 transition">
                    Cetak Laporan PDF
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-bold text-slate-500 uppercase">Total Transaksi</p>
                    <h2 class="text-3xl font-black text-slate-900 mt-3">{{ $totalTransaksi }}</h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-bold text-slate-500 uppercase">Total Pendapatan</p>
                    <h2 class="text-3xl font-black text-emerald-700 mt-3">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-bold text-slate-500 uppercase">Transaksi Success</p>
                    <h2 class="text-3xl font-black text-blue-700 mt-3">{{ $transaksiSelesai }}</h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-bold text-slate-500 uppercase">Transaksi Cancelled</p>
                    <h2 class="text-3xl font-black text-red-700 mt-3">{{ $transaksiBatal }}</h2>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm font-bold text-slate-600">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                            class="filter-input mt-2 w-full rounded-2xl border-slate-200 focus:border-emerald-600 focus:ring-emerald-600">
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-600">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                            class="filter-input mt-2 w-full rounded-2xl border-slate-200 focus:border-emerald-600 focus:ring-emerald-600">
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-600">Status</label>
                        <select name="status"
                            class="filter-input mt-2 w-full rounded-2xl border-slate-200 focus:border-emerald-600 focus:ring-emerald-600">
                            <option value="">Semua Status</option>
                            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <a href="{{ route('manager.transactions.index') }}"
                            class="w-full text-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm">
                <h2 class="text-xl font-black text-slate-900">Grafik Pendapatan Transaksi</h2>
                <p class="text-sm text-slate-500 mt-1 mb-6">
                    Pendapatan dari transaksi success berdasarkan tanggal.
                </p>

                <div class="h-80">
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">Daftar Transaksi</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Data transaksi terbaru berdasarkan filter.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kasir</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Total</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Dibayar</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kembalian
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($transactions as $transaction)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y H:i') }}
                                    </td>

                                    <td class="px-6 py-5 text-sm font-bold text-slate-900">
                                        {{ $transaction->cashier ? $transaction->cashier->first_name . ' ' . $transaction->cashier->last_name : '-' }}
                                    </td>

                                    <td class="px-6 py-5 text-sm font-black text-emerald-700">
                                        Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        Rp {{ number_format($transaction->uang_dibayar, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-black
                                            {{ $transaction->status == 'success' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $transaction->status == 'success' ? 'Success' : 'Cancelled' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada data transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6">
                    {{ $transactions->links() }}
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

        const ctx = document.getElementById('transactionChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($chartData),
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
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
    </script>
</x-app-layout>
