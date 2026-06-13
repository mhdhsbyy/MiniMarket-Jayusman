<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8">
                <h1 class="text-4xl font-black text-slate-900">Tambah Supplier</h1>
                <p class="text-slate-500 mt-2">Tambahkan data supplier baru.</p>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">Form Supplier</h2>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi data supplier minimarket.</p>
                </div>

                <form action="{{ route('owner.suppliers.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Nama Supplier</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: PT Indofood"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('nama')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Telepon</label>
                            <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="Contoh: 08123456789"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('telepon')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-black text-slate-700">Alamat</label>
                            <textarea name="alamat" rows="4" placeholder="Masukkan alamat supplier"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Status</label>
                            <select name="status"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8 pt-6 border-t border-slate-200">
                        <button class="px-6 py-3 rounded-2xl bg-emerald-600 text-white font-black">
                            Simpan Supplier
                        </button>

                        <a href="{{ route('owner.suppliers.index') }}"
                            class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-700 font-black">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
