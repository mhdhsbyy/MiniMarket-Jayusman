<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            <div class="mb-8 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest mb-2">
                        Kelola Pegawai
                    </p>
                    <h1 class="text-4xl font-black text-slate-900">
                        Gudang
                    </h1>

                    <p class="text-slate-500 mt-2">
                        Kelola data pegawai gudang pada cabang yang Anda pimpin.
                    </p>
                </div>

                <a href="{{ route('manager.warehouses.create') }}"
                    class="px-5 py-3 rounded-2xl bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-700/20 hover:bg-emerald-800 transition">
                    + Tambah Gudang
                </a>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Total Gudang</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">{{ $warehouses->count() }}</h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Pegawai terdaftar</p>
                </div>

                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Gudang Aktif</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">{{ $warehouses->where('status', 'active')->count() }}</h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Siap bertugas</p>
                </div>

                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Gudang Nonaktif</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">{{ $warehouses->where('status', 'inactive')->count() }}</h2>
                    <p class="text-xs font-semibold text-red-600 mt-3">Perlu perhatian</p>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">
                        Daftar Pegawai Gudang
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr class="text-slate-500">
                                <th class="px-6 py-4 text-left w-12">No</th>
                                <th class="px-6 py-4 text-left">Nama</th>
                                <th class="px-6 py-4 text-left">Username</th>
                                <th class="px-6 py-4 text-left">Email</th>
                                <th class="px-6 py-4 text-left">No HP</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($warehouses as $warehouse)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                    <td class="px-6 py-5 text-slate-500 font-bold text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-5 font-black text-slate-900">
                                        {{ $warehouse->first_name }} {{ $warehouse->last_name }}
                                    </td>

                                    <td class="px-6 py-5 text-slate-600">
                                        {{ $warehouse->username }}
                                    </td>

                                    <td class="px-6 py-5 text-slate-600">
                                        {{ $warehouse->email }}
                                    </td>

                                    <td class="px-6 py-5 text-slate-600">
                                        {{ $warehouse->no_hp ?? '-' }}
                                    </td>

                                    <td class="px-6 py-5">
                                        @if ($warehouse->status === 'active')
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-black">
                                                Non Aktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('manager.warehouses.edit', $warehouse->id) }}"
                                                class="px-4 py-2 rounded-xl bg-amber-100 text-amber-700 text-sm font-bold">
                                                Edit
                                            </a>

                                            <form action="{{ route('manager.warehouses.destroy', $warehouse->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')

                                                @if ($warehouse->status === 'active')
                                                    <button onclick="return confirm('Yakin ingin menonaktifkan pegawai gudang ini?')"
                                                        class="px-4 py-2 rounded-xl bg-red-100 text-red-700 text-sm font-bold">
                                                        Nonaktifkan
                                                    </button>
                                                @else
                                                    <button onclick="return confirm('Yakin ingin mengaktifkan pegawai gudang ini?')"
                                                        class="px-4 py-2 rounded-xl bg-emerald-100 text-emerald-700 text-sm font-bold">
                                                        Aktifkan
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                        Belum ada data pegawai gudang.
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
