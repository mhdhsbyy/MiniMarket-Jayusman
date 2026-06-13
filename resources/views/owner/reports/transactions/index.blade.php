<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <p class="text-sm font-black text-emerald-700 uppercase tracking-widest">
                        Laporan Transaksi
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Rekap Transaksi
                    </h1>

                    <p class="text-slate-500 mt-2">
                        Cetak laporan transaksi berdasarkan cabang dan periode tertentu.
                    </p>
                </div>

                <a href="{{ route('owner.reports.transactions.pdf', request()->query()) }}" target="_blank"
                    class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black text-sm hover:bg-emerald-800 transition">
                    Cetak PDF
                </a>
            </div>

            <form id="filterForm" method="GET" action="{{ route('owner.reports.transactions.index') }}"
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
                        <input type="date" name="start_date" id="startDateFilter" value="{{ request('start_date') }}"
                            class="mt-2 w-full rounded-2xl border-slate-200 text-sm">
                    </div>

                    <div class="flex-1">
                        <label class="text-sm font-bold text-slate-600">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="endDateFilter" value="{{ request('end_date') }}"
                            class="mt-2 w-full rounded-2xl border-slate-200 text-sm">
                    </div>

                    <a href="{{ route('owner.reports.transactions.index') }}"
                        class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 font-black text-sm text-center">
                        Reset
                    </a>
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
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
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">
                        Data Laporan Transaksi
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Data transaksi sesuai filter laporan.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[950px]">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Cabang</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kasir</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase">Total Bayar
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase">Uang
                                    Dibayar</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase">Kembalian
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($transactions as $transaction)
                                <tr class="hover:bg-slate-50 transition">
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

                                    <td class="px-6 py-5 text-right text-sm font-bold text-slate-700">
                                        Rp {{ number_format($transaction->uang_dibayar, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5 text-right text-sm font-bold text-slate-700">
                                        Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        Tidak ada data transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100">
                    {{ $transactions->links() }}
                </div>
            </div>

        </div>
    </div>

    <script>
        const filterForm = document.getElementById('filterForm');

        document.getElementById('branchFilter').addEventListener('change', function() {
            filterForm.submit();
        });

        document.getElementById('startDateFilter').addEventListener('change', function() {
            filterForm.submit();
        });

        document.getElementById('endDateFilter').addEventListener('change', function() {
            filterForm.submit();
        });
    </script>
</x-app-layout>
