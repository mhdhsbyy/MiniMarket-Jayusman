<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Cashier Panel
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Riwayat Transaksi
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Daftar transaksi yang sudah dibuat oleh kasir.
                    </p>
                </div>

                <a href="{{ route('cashier.transactions.index') }}"
                    class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black text-sm hover:bg-emerald-800 transition">
                    Transaksi Baru
                </a>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">

                <div class="p-6 border-b border-slate-200">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div class="md:col-span-2">
                            <label class="text-sm font-black text-slate-600">
                                Cari Transaksi
                            </label>

                            <input type="text" id="searchTransaction"
                                placeholder="Cari kode transaksi..."
                                autocomplete="off"
                                class="mt-2 w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="text-sm font-black text-slate-600">
                                Tanggal Mulai
                            </label>

                            <input type="date" id="filterStartDate"
                                class="mt-2 w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="text-sm font-black text-slate-600">
                                Tanggal Selesai
                            </label>

                            <input type="date" id="filterEndDate"
                                class="mt-2 w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="text-sm font-black text-slate-600">
                                Status
                            </label>

                            <select id="filterStatus"
                                class="mt-2 w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Semua Status</option>
                                <option value="success">Selesai</option>
                                <option value="cancelled">Batal</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="button" id="resetFilter"
                                class="w-full py-[13px] rounded-2xl bg-slate-100 text-slate-700 font-black text-sm hover:bg-slate-200 transition">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kode</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Item</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Total</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($transactions as $transaction)
                                @php
                                    $kodeTransaksi = 'TRX-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);
                                    $tanggalRaw = \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('Y-m-d');
                                    $tanggal = \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y H:i');
                                    $totalItem = $transaction->details->sum('jumlah');
                                @endphp

                                <tr class="transaction-row hover:bg-slate-50 transition"
                                    data-code="{{ strtolower($kodeTransaksi) }}"
                                    data-date="{{ $tanggalRaw }}"
                                    data-status="{{ $transaction->status }}">
                                    <td class="px-6 py-5 font-black text-slate-900">
                                        {{ $kodeTransaksi }}
                                    </td>

                                    <td class="px-6 py-5 text-sm font-bold text-slate-600">
                                        {{ $tanggal }}
                                    </td>

                                    <td class="px-6 py-5 text-sm font-bold text-slate-600">
                                        {{ $totalItem }} item
                                    </td>

                                    <td class="px-6 py-5 font-black text-emerald-700">
                                        Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($transaction->status == 'success')
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-black">
                                                Batal
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5 text-right">
                                        <a href="{{ route('cashier.transactions.show', $transaction->id) }}"
                                            class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-slate-500 font-bold">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="emptyTransactionRow" class="hidden">
                                <td colspan="6" class="px-6 py-16 text-center text-slate-500 font-bold">
                                    Transaksi tidak ditemukan.
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchTransaction = document.getElementById('searchTransaction');
            const filterStartDate = document.getElementById('filterStartDate');
            const filterEndDate = document.getElementById('filterEndDate');
            const filterStatus = document.getElementById('filterStatus');
            const resetFilter = document.getElementById('resetFilter');
            const transactionRows = document.querySelectorAll('.transaction-row');
            const emptyTransactionRow = document.getElementById('emptyTransactionRow');

            function filterTransactions() {
                const keyword = searchTransaction.value.toLowerCase().trim();
                const startDate = filterStartDate.value;
                const endDate = filterEndDate.value;
                const status = filterStatus.value;

                let visibleCount = 0;

                transactionRows.forEach(row => {
                    const code = row.dataset.code || '';
                    const date = row.dataset.date || '';
                    const rowStatus = row.dataset.status || '';

                    const matchSearch = code.includes(keyword);
                    const matchStatus = status === '' || rowStatus === status;
                    const matchStart = startDate === '' || date >= startDate;
                    const matchEnd = endDate === '' || date <= endDate;

                    if (matchSearch && matchStatus && matchStart && matchEnd) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (emptyTransactionRow) {
                    emptyTransactionRow.classList.toggle('hidden', visibleCount > 0);
                }
            }

            searchTransaction.addEventListener('input', filterTransactions);
            filterStartDate.addEventListener('change', filterTransactions);
            filterEndDate.addEventListener('change', filterTransactions);
            filterStatus.addEventListener('change', filterTransactions);

            resetFilter.addEventListener('click', function() {
                searchTransaction.value = '';
                filterStartDate.value = '';
                filterEndDate.value = '';
                filterStatus.value = '';

                filterTransactions();
            });
        });
    </script>
</x-app-layout>
