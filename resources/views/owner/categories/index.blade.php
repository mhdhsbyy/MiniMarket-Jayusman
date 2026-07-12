<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <p class="text-sm font-black text-emerald-700 uppercase tracking-widest mb-2">
                        Kelola Kategori
                    </p>
                    <h1 class="text-4xl font-black text-slate-900">Data Kategori Produk Mini Market</h1>
                    <p class="text-slate-500 mt-2">Kelola data kategori produk yang ada pada minimarket jayusman.</p>
                </div>

                <a href="{{ route('owner.categories.create') }}"
                    class="px-5 py-3 rounded-2xl bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-700/20 hover:bg-emerald-800 transition">
                    + Tambah Kategori
                </a>
            </div>

            @if (session('success'))
                <div
                    class="mb-6 px-5 py-4 rounded-2xl bg-emerald-50 text-emerald-700 font-bold border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 px-5 py-4 rounded-2xl bg-red-50 text-red-700 font-bold border border-red-100">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Data Kategori</h2>
                        <p class="text-sm text-slate-500 mt-1">Daftar kategori produk.</p>
                    </div>

                    <div class="w-full max-w-sm">
                        <input type="text" id="searchCategory" placeholder="Cari kategori..."
                            class="w-full px-5 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>

                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">No</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Nama Kategori
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="categoryTable" class="divide-y divide-slate-100">
                        @forelse ($categories as $category)
                            <tr class="category-row">
                                <td class="px-6 py-5 text-sm text-slate-500 row-number">{{ $loop->iteration }}</td>
                                <td class="px-6 py-5 font-bold text-slate-900 category-name">{{ $category->nama }}</td>
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('owner.categories.edit', $category->id) }}"
                                        class="px-4 mr-2 py-2 rounded-xl bg-amber-100 text-amber-700 text-sm font-bold hover:bg-amber-200">
                                        Edit
                                    </a>

                                    <form action="{{ route('owner.categories.destroy', $category->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" onclick="return confirm('Yakin hapus kategori ini?')"
                                            class="px-4 py-2 rounded-xl bg-red-100 text-red-700 text-sm font-bold">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                                    Belum ada data kategori.
                                </td>
                            </tr>
                        @endforelse

                        <tr id="emptySearchRow" class="hidden">
                            <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                                Kategori tidak ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        const searchCategory = document.getElementById('searchCategory');
        const rows = document.querySelectorAll('.category-row');
        const emptySearchRow = document.getElementById('emptySearchRow');

        searchCategory.addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();
            let visibleCount = 0;

            rows.forEach((row) => {
                const categoryName = row.querySelector('.category-name').textContent.toLowerCase();

                if (categoryName.includes(keyword)) {
                    row.classList.remove('hidden');
                    visibleCount++;
                    row.querySelector('.row-number').textContent = visibleCount;
                } else {
                    row.classList.add('hidden');
                }
            });

            if (visibleCount === 0 && rows.length > 0) {
                emptySearchRow.classList.remove('hidden');
            } else {
                emptySearchRow.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
