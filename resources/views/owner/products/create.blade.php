<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8">
                <h1 class="text-4xl font-black text-slate-900">Tambah Produk</h1>
                <p class="text-slate-500 mt-2">Tambahkan data produk baru.</p>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">Form Produk</h2>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi data produk minimarket.</p>
                </div>

                <form action="{{ route('owner.products.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Kategori</label>
                            <select name="category_id"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Supplier</label>
                            <select name="supplier_id"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Kode Produk</label>
                            <input type="text" name="kode" value="{{ old('kode') }}" placeholder="Contoh: BRG001"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('kode')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Nama Produk</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Indomie Goreng"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('nama')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Harga Beli</label>
                            <input type="number" name="harga_beli" value="{{ old('harga_beli') }}" placeholder="Contoh: 2800"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('harga_beli')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Harga Jual</label>
                            <input type="number" name="harga_jual" value="{{ old('harga_jual') }}" placeholder="Contoh: 3500"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('harga_jual')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Satuan</label>
                            <select name="satuan"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih Satuan</option>
                                @foreach (['pcs', 'botol', 'pack', 'dus', 'kg', 'liter'] as $satuan)
                                    <option value="{{ $satuan }}" {{ old('satuan') == $satuan ? 'selected' : '' }}>
                                        {{ $satuan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('satuan')
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
                            Simpan Produk
                        </button>

                        <a href="{{ route('owner.products.index') }}"
                            class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-700 font-black">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
