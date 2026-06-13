<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-4xl mx-auto px-6 py-8">

            <div class="mb-8">
                <h1 class="text-4xl font-black text-slate-900">
                    Edit Cabang
                </h1>

                <p class="text-slate-500 mt-2">
                    Perbarui informasi cabang minimarket.
                </p>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">

                <form action="{{ route('owner.branches.update', $branch->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-8 space-y-6">

                        <!-- Kode Cabang -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Kode Cabang
                            </label>

                            <input
                                type="text"
                                name="kode"
                                value="{{ old('kode', $branch->kode) }}"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">

                            @error('kode')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Cabang -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Nama Cabang
                            </label>

                            <input
                                type="text"
                                name="nama"
                                value="{{ old('nama', $branch->nama) }}"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">

                            @error('nama')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kota -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Kota
                            </label>

                            <input
                                type="text"
                                name="kota"
                                value="{{ old('kota', $branch->kota) }}"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">

                            @error('kota')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Alamat
                            </label>

                            <textarea
                                name="alamat"
                                rows="4"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">{{ old('alamat', $branch->alamat) }}</textarea>

                            @error('alamat')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Status
                            </label>

                            <select
                                name="status"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">

                                <option value="active"
                                    {{ old('status', $branch->status) == 'active' ? 'selected' : '' }}>
                                    Aktif
                                </option>

                                <option value="inactive"
                                    {{ old('status', $branch->status) == 'inactive' ? 'selected' : '' }}>
                                    Nonaktif
                                </option>

                            </select>

                            @error('status')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="border-t border-slate-200 px-8 py-5 flex justify-end gap-3">

                        <a href="{{ route('owner.branches.index') }}"
                            class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition">
                            Batal
                        </a>

                        <button type="submit"
                            class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-700/20 hover:bg-emerald-800 transition">
                            Update Cabang
                        </button>

                    </div>
                </form>

            </div>

        </div>
    </div>
</x-app-layout>
