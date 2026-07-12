<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <p class="text-sm font-black text-emerald-700 uppercase tracking-widest mb-2">
                        Kelola Produk
                    </p>
                    <h1 class="text-4xl font-black text-slate-900">Data Produk Mini Market</h1>
                    <p class="text-slate-500 mt-2">Pilih supplier untuk melihat produk yang ada pada minimarket jayusman.</p>
                </div>

                <a href="{{ route('owner.products.create') }}"
                    class="px-5 py-3 rounded-2xl bg-emerald-600 text-white font-bold hover:bg-emerald-700">
                    + Tambah Produk
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 px-5 py-4 rounded-2xl bg-emerald-50 text-emerald-700 font-bold border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Produk</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">{{ $totalProduk }}</h2>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Data Supplier</h2>
                        <p class="text-sm text-slate-500 mt-1">Daftar supplier dan jumlah produknya.</p>
                    </div>

                    <input type="text" id="searchSupplier" placeholder="Cari supplier..."
                        class="w-full md:w-72 rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">No</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Supplier</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Jumlah Produk</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($suppliers as $supplier)
                                <tr class="supplier-row hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-sm text-slate-500 row-number">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="supplier-name font-bold text-slate-900">
                                            {{ $supplier->nama }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            Klik lihat produk untuk detail
                                        </p>
                                    </td>

                                    <td class="px-6 py-5">
                                        <span class="px-4 py-2 rounded-full bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-700/20 hover:bg-emerald-800 transition">
                                            {{ $supplier->products_count }} Produk
                                        </span>
                                    </td>

                                    <td class="px-6 py-5 text-right">
                                        <a href="{{ route('owner.products.supplier', $supplier->id) }}"
                                            class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-bold hover:bg-slate-200">
                                            Lihat Produk
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada data supplier.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="emptySearchRow" class="hidden">
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                    Supplier tidak ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        const searchSupplier = document.getElementById('searchSupplier');
        const supplierRows = document.querySelectorAll('.supplier-row');
        const emptySearchRow = document.getElementById('emptySearchRow');

        searchSupplier.addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase();
            let visibleCount = 0;

            supplierRows.forEach((row) => {
                const supplierName = row.querySelector('.supplier-name').textContent.toLowerCase();

                if (supplierName.includes(keyword)) {
                    row.classList.remove('hidden');
                    visibleCount++;
                    row.querySelector('.row-number').textContent = visibleCount;
                } else {
                    row.classList.add('hidden');
                }
            });

            emptySearchRow.classList.toggle('hidden', visibleCount !== 0 || supplierRows.length === 0);
        });
    </script>
</x-app-layout>
