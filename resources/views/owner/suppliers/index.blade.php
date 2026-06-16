<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <p class="text-sm font-black text-emerald-700 uppercase tracking-widest mb-2">
                        Kelola Supplier
                    </p>
                    <h1 class="text-4xl font-black text-slate-900">Data Supplier Mini Market</h1>
                    <p class="text-slate-500 mt-2">Data supplier yang ada pada minimarket jayusman.</p>
                </div>

                <a href="{{ route('owner.suppliers.create') }}"
                    class="px-5 py-3 rounded-2xl bg-emerald-600 text-white font-bold hover:bg-emerald-700">
                    + Tambah Supplier
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 px-5 py-4 rounded-2xl bg-emerald-50 text-emerald-700 font-bold border border-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Data Supplier</h2>
                        <p class="text-sm text-slate-500 mt-1">Daftar supplier yang terdaftar.</p>
                    </div>

                    <input type="text" id="searchSupplier" placeholder="Cari supplier..."
                        class="w-full md:w-72 rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px]">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">No</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Nama Supplier</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Telepon</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Alamat</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($suppliers as $supplier)
                                <tr class="supplier-row hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-sm text-slate-500 row-number">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-5 font-bold text-slate-900 supplier-name">
                                        {{ $supplier->nama }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $supplier->telepon }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $supplier->alamat }}
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($supplier->status == 'active')
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
                                            <a href="{{ route('owner.suppliers.edit', $supplier->id) }}"
                                                class="px-4 py-2 rounded-xl bg-amber-100 text-amber-700 text-sm font-bold hover:bg-amber-200">
                                                Edit
                                            </a>

                                            <form action="{{ route('owner.suppliers.destroy', $supplier->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    onclick="return confirm('Yakin hapus supplier ini?')"
                                                    class="px-4 py-2 rounded-xl bg-red-100 text-red-700 text-sm font-bold">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada data supplier.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="emptySearchRow" class="hidden">
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
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
                const rowText = row.textContent.toLowerCase();

                if (rowText.includes(keyword)) {
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
