<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="mb-8">
                <h1 class="text-4xl font-black text-slate-900">Tambah Kategori</h1>
                <p class="text-slate-500 mt-2">Tambahkan kategori produk baru.</p>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-6">
                <form action="{{ route('owner.categories.store') }}" method="POST">
                    @csrf

                    <label class="block mb-2 text-sm font-black text-slate-700">Nama Kategori</label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">

                    @error('nama')
                        <p class="text-red-500 text-sm font-semibold mt-2">{{ $message }}</p>
                    @enderror

                    <div class="flex gap-3 mt-8 pt-6 border-t border-slate-200">
                        <button class="px-6 py-3 rounded-2xl bg-emerald-600 text-white font-black">
                            Simpan
                        </button>
                        <a href="{{ route('owner.categories.index') }}"
                            class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-700 font-black">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
