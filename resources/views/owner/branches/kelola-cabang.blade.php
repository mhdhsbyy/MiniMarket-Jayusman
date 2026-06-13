<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <p class="text-sm font-black text-emerald-700 uppercase tracking-widest">
                    Kelola Cabang
                </p>
                    <h1 class="text-4xl font-black text-slate-900 mt-2">
                        Data Cabang Mini Market
                    </h1>
                    <p class="text-slate-500 mt-2">
                        Kelola data cabang Mini Market Jayusman di berbagai kota.
                    </p>
                </div>

                <a href="{{ route('owner.branches.create') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-700/20 hover:bg-emerald-800 transition">
                    + Tambah Cabang
                </a>
            </div>

            @if (session('success'))
                <div
                    class="mb-6 rounded-2xl bg-emerald-100 border border-emerald-200 text-emerald-700 px-5 py-4 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-2xl bg-red-100 border border-red-200 text-red-700 px-5 py-4 font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Total Cabang</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $branches->count() }}
                    </h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Cabang terdaftar</p>
                </div>

                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Cabang Aktif</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $branches->where('status', 'active')->count() }}
                    </h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Beroperasi normal</p>
                </div>

                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Cabang Nonaktif</p>

                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $branches->where('status', 'inactive')->count() }}
                    </h2>

                    <p class="text-xs font-semibold text-red-600 mt-3">
                        Tidak beroperasi
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div
                    class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Data Cabang Mini Market
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Daftar cabang minimarket yang terdaftar di sistem.
                        </p>
                    </div>

                    <input type="text" id="searchBranch" placeholder="Cari cabang..."
                        class="w-full md:w-72 rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">No</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Nama Cabang
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kota</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Alamat</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($branches as $branch)
                                <tr class="branch-row hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-sm text-slate-500">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="font-bold text-slate-900">
                                            {{ $branch->nama }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            Kode: {{ $branch->kode }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 text-sm font-semibold text-slate-700">
                                        {{ $branch->kota }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $branch->alamat }}
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($branch->status == 'active')
                                            <span
                                                class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('owner.branches.show', $branch->id) }}"
                                                class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-bold hover:bg-slate-200">
                                                Detail
                                            </a>

                                            <a href="{{ route('owner.branches.edit', $branch->id) }}"
                                                class="px-4 py-2 rounded-xl bg-amber-100 text-amber-700 text-sm font-bold hover:bg-amber-200    ">
                                                Edit
                                            </a>

                                            <form action="{{ route('owner.branches.destroy', $branch->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus cabang ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="px-4 py-2 rounded-xl bg-red-50 text-red-600 text-sm font-bold hover:bg-red-100">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada data cabang.
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
        const searchBranch = document.getElementById('searchBranch');
        const branchRows = document.querySelectorAll('.branch-row');

        searchBranch.addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();

            branchRows.forEach(function(row) {
                const rowText = row.textContent.toLowerCase();

                if (rowText.includes(keyword)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>
