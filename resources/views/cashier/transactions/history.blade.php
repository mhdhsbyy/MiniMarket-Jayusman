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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Total Transaksi
                    </p>

                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $totalTransaksi }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Riwayat transaksi kasir
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Total Nominal
                    </p>

                    <h2 class="text-4xl font-black text-emerald-700 mt-3">
                        Rp {{ number_format($totalNominal, 0, ',', '.') }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Akumulasi seluruh transaksi
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Total Item Terjual
                    </p>

                    <h2 class="text-4xl font-black text-amber-700 mt-3">
                        {{ number_format($totalItem, 0, ',', '.') }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Seluruh item yang terjual
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">

                <form method="GET" id="filterForm" class="p-6 border-b border-slate-200">
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

                            <input type="text" name="tanggal_mulai" id="tanggal_mulai"
                                value="{{ request('tanggal_mulai') }}"
                                placeholder="dd/mm/yyyy"
                                class="datepicker w-full mt-2 rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="text-sm font-black text-slate-600">
                                Tanggal Selesai
                            </label>

                            <input type="text" name="tanggal_selesai" id="tanggal_selesai"
                                value="{{ request('tanggal_selesai') }}"
                                placeholder="dd/mm/yyyy"
                                class="datepicker w-full mt-2 rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="text-sm font-black text-slate-600">
                                Status
                            </label>

                            <select name="status" id="filterStatus" onchange="this.form.submit()"
                                class="mt-2 w-full rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Semua Status</option>
                                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Batal</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <a href="{{ route('cashier.transactions.history') }}"
                                class="w-full flex items-center justify-center py-[13px] rounded-2xl bg-slate-100 text-slate-700 font-black text-sm hover:bg-slate-200 transition">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase w-12">No</th>
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
                                    $tanggal = \Carbon\Carbon::parse($transaction->tanggal_transaksi)->translatedFormat('d F Y H:i');
                                    $totalItem = $transaction->details->sum('jumlah');
                                @endphp

                                <tr class="transaction-row hover:bg-slate-50 transition"
                                    data-code="{{ strtolower($kodeTransaksi) }}"
                                    data-date="{{ $tanggalRaw }}"
                                    data-status="{{ $transaction->status }}">
                                    <td class="px-6 py-5 text-sm font-black text-slate-400 text-center">
                                        {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}
                                    </td>
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
                                    <td colspan="7" class="px-6 py-16 text-center text-slate-500 font-bold">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="emptyTransactionRow" class="hidden">
                                <td colspan="7" class="px-6 py-16 text-center text-slate-500 font-bold">
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            const searchTransaction = document.getElementById('searchTransaction');
            const transactionRows = document.querySelectorAll('.transaction-row');
            const emptyTransactionRow = document.getElementById('emptyTransactionRow');

            flatpickr('#tanggal_mulai, #tanggal_selesai', {
                altInput: true,
                altFormat: 'd/m/Y',
                dateFormat: 'Y-m-d',
                locale: 'id',
                allowInput: true,
                onChange: function() {
                    filterForm.submit();
                }
            });

            function filterTransactions() {
                const keyword = searchTransaction.value.toLowerCase().trim();
                let visibleCount = 0;

                transactionRows.forEach(row => {
                    const code = row.dataset.code || '';
                    const match = code.includes(keyword);

                    if (match) {
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
        });
    </script>
</x-app-layout>
