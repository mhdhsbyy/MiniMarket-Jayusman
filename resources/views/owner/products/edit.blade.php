<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8">
                <h1 class="text-4xl font-black text-slate-900">Edit Produk</h1>
                <p class="text-slate-500 mt-2">Perbarui data produk minimarket.</p>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">Form Produk</h2>
                    <p class="text-sm text-slate-500 mt-1">Lengkapi perubahan data produk.</p>
                </div>

                <form action="{{ route('owner.products.update', $product->id) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Kategori</label>
                            <select name="category_id"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
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
                            <input type="text" value="{{ $product->kode }}"
                                class="w-full rounded-2xl border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-500 cursor-not-allowed"
                                readonly>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Nama Produk</label>
                            <input type="text" name="nama" value="{{ old('nama', $product->nama) }}"
                                placeholder="Contoh: Indomie Goreng"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('nama')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Harga Beli</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-semibold">Rp</span>
                                <input type="text" name="harga_beli" value="{{ old('harga_beli', number_format($product->harga_beli, 0, ',', '.')) }}"
                                    placeholder="Contoh: 3.000"
                                    oninput="this.value = this.value.replace(/[^\d]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 pl-10 pr-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            @error('harga_beli')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Harga Jual</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-semibold">Rp</span>
                                <input type="text" name="harga_jual" value="{{ old('harga_jual', number_format($product->harga_jual, 0, ',', '.')) }}"
                                    placeholder="Contoh: 3.500"
                                    oninput="this.value = this.value.replace(/[^\d]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 pl-10 pr-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            @error('harga_jual')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">Satuan</label>
                            <select name="satuan"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih Satuan</option>
                                @foreach (['pcs', 'botol', 'pack', 'dus', 'kg', 'liter', 'sachet', 'renceng', 'gram', 'ml'] as $satuan)
                                    <option value="{{ $satuan }}"
                                        {{ old('satuan', $product->satuan) == $satuan ? 'selected' : '' }}>
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
                                <option value="active"
                                    {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="inactive"
                                    {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                                    Nonaktif
                                </option>
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
