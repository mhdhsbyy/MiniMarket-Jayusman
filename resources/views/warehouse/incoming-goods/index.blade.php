<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-slate-900">
                        Barang Masuk
                    </h1>

                    <p class="text-slate-500 mt-2">
                        Daftar barang masuk pada cabang {{ Auth::user()->branch->nama ?? '-' }}.
                    </p>
                </div>

                <a href="{{ route('warehouse.incoming-goods.create') }}"
                    class="inline-flex justify-center px-5 py-3 rounded-2xl bg-emerald-800 text-white font-bold hover:bg-emerald-700 transition">
                    + Tambah Barang
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 px-5 py-4 rounded-2xl bg-emerald-50 text-emerald-700 font-bold border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Total Barang Masuk</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $incomingGoods->count() }}
                    </h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Transaksi barang masuk</p>
                </div>

                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Total Qty Masuk</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $incomingGoods->sum('jumlah') }}
                    </h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Jumlah stok bertambah</p>
                </div>

                <div class="bg-emerald-50 rounded-[1.7rem] p-6 border border-emerald-100 shadow-sm">
                    <p class="text-sm text-emerald-600">Barang Masuk Hari Ini</p>
                    <h2 class="text-4xl font-black text-emerald-700 mt-3">
                        {{ $incomingGoods->where('tanggal_masuk', date('Y-m-d'))->count() }}
                    </h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Transaksi hari ini</p>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Data Barang Masuk
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Riwayat barang yang masuk ke cabang Anda.
                        </p>
                    </div>

                    <input type="text"
                        id="searchIncomingGood"
                        placeholder="Cari barang masuk..."
                        class="w-full md:w-72 rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">No</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Barang</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Satuan</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Jumlah Masuk</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Keterangan</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($incomingGoods as $item)
                                <tr class="incoming-good-row hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-sm text-slate-500">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-5 text-sm font-bold text-slate-700">
                                        {{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d M Y') }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="font-bold text-slate-900">
                                            {{ $item->product->nama ?? '-' }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            Kode: {{ $item->product->kode ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $item->product->category->nama ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $item->product->satuan ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                            +{{ $item->jumlah }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $item->keterangan ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada data barang masuk untuk cabang ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        const searchIncomingGood = document.getElementById('searchIncomingGood');
        const incomingGoodRows = document.querySelectorAll('.incoming-good-row');

        searchIncomingGood.addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase();

            incomingGoodRows.forEach(function (row) {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(keyword) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>
