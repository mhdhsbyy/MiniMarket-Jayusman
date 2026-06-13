<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8">
                <h1 class="text-4xl font-black text-slate-900">
                    Tambah Barang Masuk
                </h1>

                <p class="text-slate-500 mt-2">
                    Input barang yang masuk ke cabang {{ Auth::user()->branch->nama ?? '-' }}.
                </p>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">
                        Form Barang Masuk
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Pilih produk, jumlah barang, dan tanggal masuk.
                    </p>
                </div>

                <form action="{{ route('warehouse.incoming-goods.store') }}" method="POST" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">
                                Produk
                            </label>

                            <select name="product_id"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih Produk</option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->kode }} - {{ $product->nama }}
                                    </option>
                                @endforeach
                            </select>

                            @error('product_id')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">
                                Jumlah Masuk
                            </label>

                            <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="1"
                                placeholder="Masukkan jumlah barang"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">

                            @error('jumlah')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">
                                Tanggal Masuk
                            </label>

                            <input type="date" name="tanggal_masuk"
                                value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">

                            @error('tanggal_masuk')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-black text-slate-700">
                                Keterangan
                            </label>

                            <textarea name="keterangan" rows="4"
                                placeholder="Contoh: Restock dari supplier"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('keterangan') }}</textarea>

                            @error('keterangan')
                                <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-200">
                        <button type="submit"
                            class="px-6 py-3 rounded-2xl bg-emerald-600 text-white font-black hover:bg-emerald-700 transition">
                            Simpan Barang Masuk
                        </button>

                        <a href="{{ route('warehouse.incoming-goods.index') }}"
                            class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-700 font-black hover:bg-slate-200 transition">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
