<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Warehouse Panel
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Barang Masuk
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Kelola data barang masuk dan pembaruan stok cabang.
                    </p>
                </div>

                <a href="{{ route('warehouse.incoming-goods.create') }}"
                    class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black hover:bg-emerald-800 transition shadow-lg shadow-emerald-900/20 text-center">
                    Tambah Barang Masuk
                </a>
            </div>

            @if (session('success'))
                <div class="bg-emerald-100 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl font-bold">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Total Data Masuk
                    </p>

                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $totalBarangMasuk }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Riwayat barang masuk
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Total Jumlah Masuk
                    </p>

                    <h2 class="text-4xl font-black text-emerald-700 mt-3">
                        {{ number_format($totalJumlahMasuk, 0, ',', '.') }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Akumulasi semua barang
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Masuk Hari Ini
                    </p>

                    <h2 class="text-4xl font-black text-amber-600 mt-3">
                        {{ number_format($barangMasukHariIni, 0, ',', '.') }}
                    </h2>

                    <p class="text-sm text-slate-400 mt-2">
                        Jumlah barang hari ini
                    </p>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">

                <div class="p-6 border-b border-slate-200 space-y-5">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Riwayat Barang Masuk
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Data barang masuk berdasarkan cabang Anda.
                        </p>
                    </div>

                    <form id="incomingFilterForm" method="GET" class="grid grid-cols-1 lg:grid-cols-4 gap-3">
                        <div class="lg:col-span-2">
                            <input type="text"
                                name="search"
                                id="incomingSearch"
                                value="{{ request('search') }}"
                                placeholder="Cari produk, kode, kategori, atau supplier..."
                                class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-600 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <input type="date"
                            name="tanggal_awal"
                            id="tanggalAwal"
                            value="{{ request('tanggal_awal') }}"
                            class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-600 focus:border-emerald-500 focus:ring-emerald-500">

                        <input type="date"
                            name="tanggal_akhir"
                            id="tanggalAkhir"
                            value="{{ request('tanggal_akhir') }}"
                            class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-600 focus:border-emerald-500 focus:ring-emerald-500">
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Produk</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Supplier</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase text-center">Jumlah</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Harga Beli</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($incomingGoods as $item)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-5">
                                        <p class="font-black text-slate-900">
                                            {{ $item->product->nama ?? '-' }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-1">
                                            {{ $item->product->kode ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600 font-bold">
                                        {{ $item->product->supplier->nama ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5 text-center">
                                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black">
                                            +{{ $item->jumlah }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5 font-black text-slate-900">
                                        Rp {{ number_format($item->harga_beli, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($item->tanggal_masuk)->translatedFormat('d F Y') }}
                                    </td>

                                    <td class="px-6 py-5 text-right">
                                        <a href="{{ route('warehouse.incoming-goods.show', $item) }}"
                                            class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-slate-500">
                                        Data barang masuk tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($incomingGoods->hasPages())
                    <div class="px-6 py-5 border-t border-slate-200">
                        {{ $incomingGoods->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>

    <script>
        const incomingFilterForm = document.getElementById('incomingFilterForm');
        const incomingSearch = document.getElementById('incomingSearch');
        const tanggalAwal = document.getElementById('tanggalAwal');
        const tanggalAkhir = document.getElementById('tanggalAkhir');

        let incomingTypingTimer;

        incomingSearch?.addEventListener('input', function () {
            clearTimeout(incomingTypingTimer);

            incomingTypingTimer = setTimeout(() => {
                incomingFilterForm.submit();
            }, 400);
        });

        tanggalAwal?.addEventListener('change', function () {
            incomingFilterForm.submit();
        });

        tanggalAkhir?.addEventListener('change', function () {
            incomingFilterForm.submit();
        });
    </script>
</x-app-layout>
