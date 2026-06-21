<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            @php
                $branchName = Auth::user()->branch->nama ?? 'Cabang';

                $branchInitial = collect(explode(' ', $branchName))
                    ->filter()
                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                    ->take(2)
                    ->implode('');
            @endphp

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Supervisor Dashboard
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Selamat Datang, {{ Auth::user()->first_name }} 👀
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Awasi transaksi, stok, dan aktivitas operasional cabang hari ini.
                    </p>
                </div>

                <div class="flex items-center gap-4 lg:text-right">

                    <div class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center">
                        <span class="text-emerald-700 text-xl font-black">
                            {{ $branchInitial }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-black text-slate-900">
                            {{ $branchName }}
                        </h3>

                        <p class="text-sm text-emerald-700">
                            Supervisor Cabang
                        </p>
                    </div>

                </div>

            </div>

            {{-- Statistik Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Transaksi Hari Ini
                    </p>

                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $totalTransaksiHariIni }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Pendapatan Hari Ini
                    </p>

                    <h2 class="text-3xl font-black text-emerald-700 mt-3">
                        Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Produk Menipis
                    </p>

                    <h2 class="text-4xl font-black text-red-600 mt-3">
                        {{ $produkMenipis }}
                    </h2>
                </div>

                <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                    <p class="text-sm font-black text-slate-500 uppercase">
                        Total Produk
                    </p>

                    <h2 class="text-4xl font-black text-slate-900 mt-3">
                        {{ $totalProduk }}
                    </h2>
                </div>

            </div>

            {{-- Ringkasan Pengawasan --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-black text-slate-900">
                                Pendapatan 7 Hari Terakhir
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                Grafik pendapatan transaksi selesai cabang {{ $branchName }}.
                            </p>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="h-[360px]">
                            <canvas id="incomeChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200">
                        <p class="text-xs font-black text-emerald-700 uppercase tracking-widest">
                            Fokus Pengawasan
                        </p>

                        <h2 class="text-xl font-black text-slate-900 mt-2 leading-snug">
                            Ringkasan operasional cabang hari ini
                        </h2>
                    </div>

                    <div class="p-6 space-y-4">

                        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                            <p class="text-sm font-bold text-slate-500">
                                Status Transaksi
                            </p>

                            <p class="text-2xl font-black text-emerald-700 mt-2">
                                {{ $totalTransaksiHariIni }} Transaksi
                            </p>

                            <p class="text-xs text-slate-500 mt-1">
                                Aktivitas transaksi cabang hari ini.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-red-50 border border-red-100">
                            <p class="text-sm font-bold text-slate-500">
                                Perlu Perhatian
                            </p>

                            <p class="text-2xl font-black text-red-600 mt-2">
                                {{ $produkMenipis }} Produk
                            </p>

                            <p class="text-xs text-slate-500 mt-1">
                                Produk dengan stok mulai menipis.
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <a href="{{ route('supervisor.transactions.index') }}"
                                class="text-center px-4 py-3 rounded-2xl bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-700/20 hover:bg-emerald-800 transition">
                                Transaksi
                            </a>

                            <a href="{{ route('supervisor.stocks.index') }}"
                                class="text-center px-4 py-3 rounded-2xl  bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-700/20 hover:bg-emerald-800 transition">
                                Stok
                            </a>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Tabel --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Transaksi Terbaru --}}
                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden h-full">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-black text-slate-900">
                            Transaksi Terbaru
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            5 transaksi terakhir dari cabang ini.
                        </p>
                    </div>

                    <div class="p-6 space-y-4 min-h-[420px]">
                        @forelse ($transaksiTerbaru as $transaction)
                            <div
                                class="flex items-center justify-between gap-4 p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 transition">
                                <div class="min-w-0">
                                    <p class="font-black text-slate-900 truncate">
                                        Kasir:
                                        {{ $transaction->cashier->first_name ?? '-' }}
                                        {{ $transaction->cashier->last_name ?? '' }}
                                    </p>

                                    <p class="text-sm text-slate-500 mt-1">
                                        {{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y H:i') }}
                                    </p>
                                </div>

                                <div class="shrink-0 text-right">
                                    <p class="font-black text-emerald-700">
                                        Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                                    </p>

                                    <p class="text-xs text-slate-400 mt-1">
                                        Total Bayar
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="h-[320px] flex items-center justify-center text-slate-500">
                                Belum ada transaksi.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Stok Menipis --}}
                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden h-full">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-black text-slate-900">
                            Stok Menipis
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            5 produk yang perlu segera diperhatikan.
                        </p>
                    </div>

                    <div class="p-6 space-y-4 min-h-[420px]">
                        @forelse ($stokMenipis as $stock)
                            <div
                                class="flex items-center justify-between gap-4 p-4 rounded-2xl border border-red-100 bg-red-50/50 hover:bg-red-50 transition">
                                <div class="min-w-0">
                                    <p class="font-black text-slate-900 truncate">
                                        {{ $stock->product->nama ?? '-' }}
                                    </p>

                                    <p class="text-sm text-slate-500 mt-1">
                                        {{ $stock->product->category->nama ?? '-' }}
                                        • Kode: {{ $stock->product->kode ?? '-' }}
                                    </p>
                                </div>

                                <div class="shrink-0 text-right">
                                    <p class="text-xs font-black text-slate-400 uppercase">
                                        Stok
                                    </p>

                                    <span
                                        class="inline-flex mt-1 px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-black">
                                        {{ $stock->jumlah_stok }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="h-[320px] flex items-center justify-center text-slate-500">
                                Tidak ada stok menipis.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('incomeChart');

            if (ctx) {
                const dataPendapatan = @json($dataPendapatan);
                const maxPendapatan = Math.max(...dataPendapatan, 0);
                const suggestedMax = maxPendapatan + (maxPendapatan * 0.25);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: 'Pendapatan',
                            data: dataPendapatan,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(context
                                            .raw);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: suggestedMax,
                                ticks: {
                                    maxTicksLimit: 6,
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    }
                                },
                                grid: {
                                    drawBorder: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
