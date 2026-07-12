<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <a href="{{ route('owner.products.index') }}"
                        class="text-sm font-bold text-emerald-700 hover:text-emerald-800">
                        ← Kembali
                    </a>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Produk {{ $supplier->nama }}
                    </h1>

                    <p class="text-slate-500 mt-2">
                        Daftar produk dari supplier {{ $supplier->nama }}.
                    </p>
                </div>

                <a href="{{ route('owner.products.create') }}"
                    class="px-5 py-3 rounded-2xl bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-700/20 hover:bg-emerald-800 transition">
                    + Tambah Produk
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 px-5 py-4 rounded-2xl bg-emerald-50 text-emerald-700 font-bold border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 px-5 py-4 rounded-2xl bg-red-50 text-red-700 font-bold border border-red-100">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Total Produk</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">{{ $products->count() }}</h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Produk terdaftar</p>
                </div>

                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Produk Aktif</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">{{ $products->where('status', 'active')->count() }}</h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Produk tersedia</p>
                </div>

                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Produk Nonaktif</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">{{ $products->where('status', 'inactive')->count() }}</h2>
                    <p class="text-xs font-semibold text-red-600 mt-3">Produk tidak aktif</p>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Data Produk</h2>
                        <p class="text-sm text-slate-500 mt-1">Total {{ $products->count() }} produk.</p>
                    </div>

                    <input type="text" id="searchProduct" placeholder="Cari produk..."
                        class="w-full md:w-72 rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px]">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">No</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Produk</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Harga Beli</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Harga Jual</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Satuan</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($products as $product)
                                <tr class="product-row hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-sm text-slate-500 row-number">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="product-name font-bold text-slate-900">{{ $product->nama }}</p>
                                        <p class="text-sm text-slate-500">Kode: {{ $product->kode }}</p>
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $product->category->nama ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5 text-sm font-bold text-slate-700">
                                        Rp {{ number_format($product->harga_beli, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5 text-sm font-bold text-slate-900">
                                        Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $product->satuan }}
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($product->status == 'active')
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('owner.products.edit', $product->id) }}"
                                                class="px-4 py-2 rounded-xl bg-amber-100 text-amber-700 text-sm font-bold hover:bg-amber-200">
                                                Edit
                                            </a>

                                            <form action="{{ route('owner.products.destroy', $product->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    onclick="return confirm('Yakin hapus produk ini?')"
                                                    class="px-4 py-2 rounded-xl bg-red-100 text-red-700 text-sm font-bold">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada produk dari supplier ini.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="emptySearchRow" class="hidden">
                                <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                    Produk tidak ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        const searchProduct = document.getElementById('searchProduct');
        const productRows = document.querySelectorAll('.product-row');
        const emptySearchRow = document.getElementById('emptySearchRow');

        searchProduct.addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase();
            let visibleCount = 0;

            productRows.forEach((row) => {
                const rowText = row.textContent.toLowerCase();

                if (rowText.includes(keyword)) {
                    row.classList.remove('hidden');
                    visibleCount++;
                    row.querySelector('.row-number').textContent = visibleCount;
                } else {
                    row.classList.add('hidden');
                }
            });

            emptySearchRow.classList.toggle('hidden', visibleCount !== 0 || productRows.length === 0);
        });
    </script>
</x-app-layout>
