<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-5xl mx-auto px-6 py-8 space-y-8">

            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Owner Panel
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        BM-{{ str_pad($incomingGood->id, 5, '0', STR_PAD_LEFT) }}
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Detail histori barang masuk seluruh cabang.
                    </p>
                </div>

                <a href="{{ route('owner.monitoring-incoming-goods.index') }}"
                    class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-700 font-black hover:bg-slate-200 transition text-center">
                    Kembali
                </a>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">
                        Informasi Barang Masuk
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Data ini bersifat histori dan tidak dapat diedit.
                    </p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                        <p class="text-xs font-black text-slate-500 uppercase">Produk</p>
                        <h3 class="text-lg font-black text-slate-900 mt-2">
                            {{ $incomingGood->product->nama ?? '-' }}
                        </h3>
                        <p class="text-sm text-slate-500 mt-1">
                            {{ $incomingGood->product->kode ?? '-' }} • {{ $incomingGood->product->category->nama ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                        <p class="text-xs font-black text-slate-500 uppercase">Supplier</p>
                        <h3 class="text-lg font-black text-slate-900 mt-2">
                            {{ $incomingGood->product->supplier->nama ?? '-' }}
                        </h3>
                    </div>

                    <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-5">
                        <p class="text-xs font-black text-emerald-700 uppercase">Jumlah Masuk</p>
                        <h3 class="text-3xl font-black text-emerald-700 mt-2">
                            +{{ number_format($incomingGood->jumlah, 0, ',', '.') }}
                        </h3>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                        <p class="text-xs font-black text-slate-500 uppercase">Harga Beli</p>
                        <h3 class="text-2xl font-black text-slate-900 mt-2">
                            Rp {{ number_format($incomingGood->harga_beli, 0, ',', '.') }}
                        </h3>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                        <p class="text-xs font-black text-slate-500 uppercase">Total Nilai Barang</p>
                        <h3 class="text-2xl font-black text-slate-900 mt-2">
                            Rp {{ number_format($incomingGood->jumlah * $incomingGood->harga_beli, 0, ',', '.') }}
                        </h3>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                        <p class="text-xs font-black text-slate-500 uppercase">Tanggal Masuk</p>
                        <h3 class="text-lg font-black text-slate-900 mt-2">
                            {{ \Carbon\Carbon::parse($incomingGood->tanggal_masuk)->translatedFormat('d F Y H:i') }}
                        </h3>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                        <p class="text-xs font-black text-slate-500 uppercase">Petugas</p>
                        <h3 class="text-lg font-black text-slate-900 mt-2">
                            {{ $incomingGood->user->first_name ?? '-' }} {{ $incomingGood->user->last_name ?? '' }}
                        </h3>
                    </div>

                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                        <p class="text-xs font-black text-slate-500 uppercase">Cabang</p>
                        <h3 class="text-lg font-black text-slate-900 mt-2">
                            {{ $incomingGood->branch->nama ?? '-' }}
                        </h3>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
