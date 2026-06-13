<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8">
                <h1 class="text-4xl font-black text-slate-900">Edit Supplier</h1>
                <p class="text-slate-500 mt-2">Perbarui data supplier.</p>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">Form Supplier</h2>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi perubahan data supplier.</p>
                </div>

                <form action="{{ route('owner.suppliers.update', $supplier->id) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Nama Supplier</label>
                            <input type="text" name="nama" value="{{ old('nama', $supplier->nama) }}"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('nama')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Telepon</label>
                            <input type="text" name="telepon" value="{{ old('telepon', $supplier->telepon) }}"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('telepon')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-black text-slate-700">Alamat</label>
                            <textarea name="alamat" rows="4"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('alamat', $supplier->alamat) }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Status</label>
                            <select name="status"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8 pt-6 border-t border-slate-200">
                        <button class="px-6 py-3 rounded-2xl bg-emerald-600 text-white font-black">
                            Simpan Perubahan
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
