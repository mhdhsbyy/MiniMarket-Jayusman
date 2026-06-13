<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-black text-slate-900">Detail Cabang</h1>
                    <p class="text-slate-500 mt-2">Profil cabang, karyawan, dan stok produk.</p>
                </div>

                <a href="{{ route('owner.branches.index') }}"
                    class="px-5 py-3 rounded-2xl bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-700/20 hover:bg-emerald-800 transition">
                    Kembali
                </a>
            </div>

            {{-- Informasi Cabang --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden mb-6">
                <div class="p-8">

                    <div class="flex items-start justify-between">

                        <div>

                            <span
                                class="inline-flex px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black uppercase">
                                {{ $branch->kode }}
                            </span>

                            <h2 class="text-4xl font-black text-slate-900 mt-5">
                                {{ $branch->nama }}
                            </h2>

                            <p class="text-lg text-slate-500 mt-2">
                                {{ $branch->kota }} • {{ $branch->alamat }}
                            </p>

                        </div>

                        @if ($branch->status == 'active')
                            <span class="px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-sm font-bold">
                                Aktif
                            </span>
                        @else
                            <span class="px-4 py-2 rounded-full bg-red-100 text-red-700 text-sm font-bold">
                                Nonaktif
                            </span>
                        @endif

                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-100">

                        <p class="text-sm font-bold text-slate-500 mb-4">
                            Manager Cabang
                        </p>

                        @if ($branch->manager)
                            <div class="flex items-center gap-4">

                                <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center">
                                    <span class="font-black text-emerald-700">
                                        {{ strtoupper(substr($branch->manager->first_name, 0, 1)) }}
                                    </span>
                                </div>

                                <div>
                                    <p class="font-black text-slate-900">
                                        {{ $branch->manager->first_name }}
                                        {{ $branch->manager->last_name }}
                                    </p>

                                    <p class="text-slate-500 text-sm">
                                        {{ $branch->manager->email }}
                                    </p>
                                </div>

                            </div>
                        @else
                            <p class="text-slate-500">
                                Belum ada manager cabang.
                            </p>
                        @endif

                    </div>

                </div>
            </div>

            {{-- Ringkasan Cabang --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Produk</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">{{ $totalProduk }}</h2>
                    <p class="text-slate-500 mt-2">Produk di cabang ini</p>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Karyawan</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">{{ $totalKaryawan }}</h2>
                    <p class="text-slate-500 mt-2">Karyawan cabang</p>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">Total Stok</p>
                    <h2 class="text-4xl font-black text-emerald-600 mt-3">
                        {{ number_format($totalStok, 0, ',', '.') }}
                    </h2>
                    <p class="text-slate-500 mt-2">Jumlah seluruh stok</p>
                </div>
            </div>

            {{-- Karyawan Cabang --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden mb-6">
                <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Karyawan Cabang</h2>
                        <p class="text-sm text-slate-500 mt-1">Daftar karyawan yang bekerja di cabang ini.</p>
                    </div>

                    <span class="px-4 py-2 rounded-full bg-slate-100 text-slate-700 text-sm font-bold">
                        {{ $totalKaryawan }} Karyawan
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px]">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">No</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Nama</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Jabatan</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($branch->employees as $employee)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-sm text-slate-500">{{ $loop->iteration }}</td>

                                    <td class="px-6 py-5 font-bold text-slate-900">
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $employee->email }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <span
                                            class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                                            {{ ucfirst($employee->getRoleNames()->first()) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($employee->status == 'active')
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada karyawan di cabang ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Produk / Stok Cabang --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">Produk / Stok Cabang</h2>
                        <p class="text-sm text-slate-500 mt-1">Daftar produk dan jumlah stok pada cabang ini.</p>
                    </div>

                    <span class="px-4 py-2 rounded-full bg-emerald-100 text-emerald-700 text-sm font-bold">
                        {{ $totalProduk }} Produk
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px]">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">No</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Produk</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kategori
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Supplier
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Stok</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Satuan</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($branch->stocks as $stock)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-sm text-slate-500">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="font-bold text-slate-900">
                                            {{ $stock->product->nama ?? '-' }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            Kode: {{ $stock->product->kode ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $stock->product->category->nama ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $stock->product->supplier->nama ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($stock->jumlah_stok <= 30)
                                            <span class="text-lg font-black text-red-600">
                                                {{ $stock->jumlah_stok }}
                                            </span>
                                        @else
                                            <span class="text-lg font-black text-emerald-600">
                                                {{ $stock->jumlah_stok }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        {{ $stock->product->satuan ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5">
                                        @if (($stock->product->status ?? '') == 'active')
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada produk atau stok di cabang ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
