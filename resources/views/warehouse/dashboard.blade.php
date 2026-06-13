<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <!-- Header -->
            <div class="mb-8">
                <div class="rounded-[2rem] bg-gradient-to-br from-[#07150f] to-emerald-900 p-8 text-white shadow-xl">
                    <p class="text-sm font-semibold text-emerald-300 uppercase tracking-widest">
                        Warehouse Dashboard
                    </p>

                    <h1 class="text-4xl font-black mt-3">
                        Halo, {{ Auth::user()->first_name }}
                    </h1>

                    <p class="text-emerald-100 mt-3">
                        Anda sedang mengelola stok barang untuk cabang:
                    </p>

                    <div class="mt-6 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-7 h-7 text-emerald-300"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 21h18M5 21V7l7-4 7 4v14M9 10h6M9 14h6" />
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-xl font-black text-white">
                                {{ Auth::user()->branch->nama ?? 'Cabang Tidak Ditemukan' }}
                            </h3>

                            <p class="text-sm text-emerald-200">
                                {{ Auth::user()->branch->kota ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-[1.7rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Total Produk</p>
                    <h2 class="text-4xl font-black text-slate-900 mt-3">125</h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Tersedia di gudang</p>
                </div>

                <div class="bg-emerald-50 rounded-[1.7rem] p-6 border border-emerald-100 shadow-sm">
                    <p class="text-sm text-emerald-700">Barang Masuk</p>
                    <h2 class="text-4xl font-black text-emerald-800 mt-3">35</h2>
                    <p class="text-xs font-semibold text-emerald-600 mt-3">Hari ini</p>
                </div>

                <div class="bg-blue-50 rounded-[1.7rem] p-6 border border-blue-100 shadow-sm">
                    <p class="text-sm text-blue-700">Barang Keluar</p>
                    <h2 class="text-4xl font-black text-blue-800 mt-3">18</h2>
                    <p class="text-xs font-semibold text-blue-600 mt-3">Hari ini</p>
                </div>

                <div class="bg-red-50 rounded-[1.7rem] p-6 border border-red-100 shadow-sm">
                    <p class="text-sm text-red-500">Stok Menipis</p>
                    <h2 class="text-4xl font-black text-red-600 mt-3">7</h2>
                    <p class="text-xs font-semibold text-red-500 mt-3">Perlu restock</p>
                </div>
            </div>

            <!-- Chart -->
            <div class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Pergerakan Stok
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Barang masuk dan barang keluar cabang
                        </p>
                    </div>

                    <span class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold">
                        Bulan Ini
                    </span>
                </div>

                <div class="h-80 rounded-[1.5rem] bg-slate-50 border border-dashed border-slate-300 flex items-center justify-center">
                    <p class="text-slate-400 text-sm">
                        Chart stok akan ditampilkan di sini
                    </p>
                </div>
            </div>

            <!-- Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Stok Menipis -->
                <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-black text-slate-900">
                                Stok Menipis
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">
                                Produk yang perlu segera dilakukan restock.
                            </p>
                        </div>

                        <a href="#"
                            class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-bold hover:bg-slate-200">
                            Lihat Stok
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Produk</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kategori</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Stok</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="px-6 py-5">
                                        <p class="font-bold text-slate-900">Minyak Goreng</p>
                                        <p class="text-xs text-slate-500">BRG001</p>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-slate-600">Sembako</td>
                                    <td class="px-6 py-5 text-sm font-black text-red-600">5 pcs</td>
                                    <td class="px-6 py-5">
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                            Menipis
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-6 py-5">
                                        <p class="font-bold text-slate-900">Gula Pasir</p>
                                        <p class="text-xs text-slate-500">BRG002</p>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-slate-600">Sembako</td>
                                    <td class="px-6 py-5 text-sm font-black text-red-600">8 pcs</td>
                                    <td class="px-6 py-5">
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                            Menipis
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Action -->
                <div class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm">
                    <h2 class="text-xl font-black text-slate-900">
                        Aksi Cepat
                    </h2>

                    <p class="text-sm text-slate-500 mt-1 mb-6">
                        Pilih aktivitas gudang yang ingin dilakukan.
                    </p>

                    <div class="space-y-4">
                        <a href="#"
                            class="block p-5 rounded-2xl bg-emerald-50 border border-emerald-100 hover:shadow-md transition">
                            <p class="font-black text-emerald-800">
                                Barang Masuk
                            </p>
                            <p class="text-sm text-emerald-600 mt-1">
                                Catat barang yang masuk ke gudang.
                            </p>
                        </a>

                        <a href="#"
                            class="block p-5 rounded-2xl bg-blue-50 border border-blue-100 hover:shadow-md transition">
                            <p class="font-black text-blue-800">
                                Barang Keluar
                            </p>
                            <p class="text-sm text-blue-600 mt-1">
                                Catat barang keluar dari gudang.
                            </p>
                        </a>

                        <a href="#"
                            class="block p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-md transition">
                            <p class="font-black text-slate-900">
                                Cek Stok Barang
                            </p>
                            <p class="text-sm text-slate-500 mt-1">
                                Lihat stok barang cabang saat ini.
                            </p>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
