<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Kasir
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Transaksi Penjualan
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Buat transaksi penjualan untuk cabang
                        <span class="font-bold text-slate-700">
                            {{ Auth::user()->branch->nama ?? 'Cabang' }}
                        </span>.
                    </p>
                </div>
            </div>

            @if (session('success'))
                <div class="p-5 rounded-2xl bg-emerald-100 text-emerald-700 font-bold">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-5 rounded-2xl bg-red-100 text-red-700 font-bold">
                    {{ session('error') }}
                </div>
            @endif

            {{-- POS --}}
            <form method="POST" action="{{ route('cashier.transactions.store') }}" id="transactionForm">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Product List --}}
                    <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-black text-slate-900">
                                    Pilih Produk
                                </h2>
                                <p class="text-sm text-slate-500 mt-1">
                                    Cari dan pilih produk yang dibeli pelanggan.
                                </p>
                            </div>

                            <input type="text" id="searchProduct" placeholder="Cari produk..."
                                class="w-full md:w-72 rounded-2xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[620px] overflow-y-auto">
                            @forelse ($products as $product)
                                @php
                                    $stock = $product->stocks->where('branch_id', Auth::user()->branch_id)->first();
                                    $jumlahStok = $stock->jumlah_stok ?? 0;
                                @endphp

                                <button type="button"
                                    class="product-card text-left p-5 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-emerald-50 hover:border-emerald-200 transition"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->nama }}"
                                    data-code="{{ $product->kode }}"
                                    data-price="{{ $product->harga_jual }}"
                                    data-stock="{{ $jumlahStok }}"
                                    data-search="{{ strtolower($product->kode . ' ' . $product->nama . ' ' . ($product->category->nama ?? '')) }}">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="font-black text-slate-900">
                                                {{ $product->nama }}
                                            </p>
                                            <p class="text-sm text-slate-500 mt-1">
                                                {{ $product->kode }} • {{ $product->category->nama ?? '-' }}
                                            </p>
                                        </div>

                                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black">
                                            Stok {{ $jumlahStok }}
                                        </span>
                                    </div>

                                    <p class="text-lg font-black text-emerald-700 mt-4">
                                        Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                                    </p>
                                </button>
                            @empty
                                <div class="md:col-span-2 py-16 text-center text-slate-500">
                                    Tidak ada produk tersedia.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Cart --}}
                    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-black text-slate-900">
                                Keranjang
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">
                                Detail produk yang dibeli.
                            </p>
                        </div>

                        <div class="p-6 space-y-5">
                            <div id="cartItems" class="space-y-3">
                                <div id="emptyCart" class="py-10 text-center text-slate-500 text-sm">
                                    Belum ada produk dipilih.
                                </div>
                            </div>

                            <div class="border-t border-slate-200 pt-5 space-y-4">
                                <div class="flex items-center justify-between">
                                    <p class="font-black text-slate-500 uppercase text-sm">Total</p>
                                    <p class="text-2xl font-black text-emerald-700" id="totalText">
                                        Rp 0
                                    </p>
                                </div>

                                <div>
                                    <label class="text-sm font-black text-slate-600">
                                        Uang Dibayar
                                    </label>
                                    <input type="number" name="uang_dibayar" id="uangDibayar" min="0"
                                        class="mt-2 w-full rounded-2xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                                        placeholder="Masukkan uang dibayar" required>
                                </div>

                                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50">
                                    <p class="font-black text-slate-500 uppercase text-sm">Kembalian</p>
                                    <p class="text-xl font-black text-slate-900" id="kembalianText">
                                        Rp 0
                                    </p>
                                </div>

                                <button type="submit"
                                    class="w-full px-6 py-4 rounded-2xl bg-emerald-700 text-white font-black text-sm hover:bg-emerald-800 transition">
                                    Simpan Transaksi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Riwayat --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Riwayat Transaksi
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Transaksi yang dibuat oleh kasir saat ini.
                        </p>
                    </div>

                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 w-full md:w-auto">
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                            class="rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">

                        <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                            class="rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">

                        <select name="status"
                            class="rounded-2xl border-slate-200 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Status</option>
                            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Batal</option>
                        </select>

                        <button type="submit"
                            class="px-5 py-3 rounded-2xl bg-emerald-700 text-white text-sm font-black hover:bg-emerald-800 transition">
                            Filter
                        </button>
                    </form>
                </div>

                <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <input type="text" id="searchTransaction" placeholder="Cari transaksi..."
                        class="w-full md:w-72 rounded-2xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500">

                    <a href="{{ route('cashier.transactions.index') }}"
                        class="px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition text-center">
                        Reset Filter
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kode</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Total</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Dibayar</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kembalian</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($transactions as $transaction)
                                @php
                                    $kodeTransaksi = 'TRX-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);
                                    $tanggalTransaksi = \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d M Y H:i');
                                    $statusLabel = $transaction->status == 'success' ? 'Selesai' : 'Batal';
                                @endphp

                                <tr class="transaction-row hover:bg-slate-50 transition"
                                    data-search="{{ strtolower($kodeTransaksi . ' ' . $tanggalTransaksi . ' ' . $transaction->total_bayar . ' ' . $transaction->uang_dibayar . ' ' . $transaction->kembalian . ' ' . $statusLabel) }}">
                                    <td class="px-6 py-5 font-black text-slate-900">{{ $kodeTransaksi }}</td>
                                    <td class="px-6 py-5 text-sm text-slate-600">{{ $tanggalTransaksi }}</td>
                                    <td class="px-6 py-5 font-black text-emerald-700">
                                        Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        Rp {{ number_format($transaction->uang_dibayar, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-5 text-sm text-slate-600">
                                        Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-5">
                                        @if ($transaction->status == 'success')
                                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-black">
                                                Batal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <a href="{{ route('cashier.transactions.show', $transaction->id) }}"
                                            class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-black hover:bg-slate-200 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center text-slate-500">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="emptySearchRow" class="hidden">
                                <td colspan="7" class="px-6 py-16 text-center text-slate-500">
                                    Data transaksi tidak ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-200">
                    {{ $transactions->links() }}
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formatRupiah = (number) => {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            };

            const cartItems = document.getElementById('cartItems');
            const emptyCart = document.getElementById('emptyCart');
            const totalText = document.getElementById('totalText');
            const uangDibayar = document.getElementById('uangDibayar');
            const kembalianText = document.getElementById('kembalianText');
            const form = document.getElementById('transactionForm');

            let cart = {};

            function renderCart() {
                cartItems.innerHTML = '';
                let total = 0;
                let index = 0;

                Object.values(cart).forEach(item => {
                    total += item.price * item.qty;

                    cartItems.innerHTML += `
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <input type="hidden" name="products[${index}][product_id]" value="${item.id}">
                            <input type="hidden" name="products[${index}][jumlah]" value="${item.qty}">

                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-black text-slate-900">${item.name}</p>
                                    <p class="text-sm text-slate-500">${formatRupiah(item.price)} x ${item.qty}</p>
                                </div>

                                <button type="button" class="remove-item text-red-600 font-black" data-id="${item.id}">
                                    ×
                                </button>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center gap-2">
                                    <button type="button" class="decrease-item w-8 h-8 rounded-xl bg-white border font-black" data-id="${item.id}">-</button>
                                    <span class="font-black text-slate-900">${item.qty}</span>
                                    <button type="button" class="increase-item w-8 h-8 rounded-xl bg-white border font-black" data-id="${item.id}">+</button>
                                </div>

                                <p class="font-black text-emerald-700">
                                    ${formatRupiah(item.price * item.qty)}
                                </p>
                            </div>
                        </div>
                    `;

                    index++;
                });

                if (Object.keys(cart).length === 0) {
                    cartItems.innerHTML = `
                        <div id="emptyCart" class="py-10 text-center text-slate-500 text-sm">
                            Belum ada produk dipilih.
                        </div>
                    `;
                }

                totalText.innerText = formatRupiah(total);
                updateKembalian();
            }

            function updateKembalian() {
                let total = 0;

                Object.values(cart).forEach(item => {
                    total += item.price * item.qty;
                });

                const bayar = parseInt(uangDibayar.value || 0);
                const kembalian = bayar - total;

                kembalianText.innerText = formatRupiah(kembalian > 0 ? kembalian : 0);
            }

            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const price = parseInt(this.dataset.price);
                    const stock = parseInt(this.dataset.stock);

                    if (!cart[id]) {
                        cart[id] = {
                            id,
                            name,
                            price,
                            stock,
                            qty: 1
                        };
                    } else {
                        if (cart[id].qty >= stock) {
                            alert('Stok tidak mencukupi.');
                            return;
                        }

                        cart[id].qty++;
                    }

                    renderCart();
                });
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('increase-item')) {
                    const id = e.target.dataset.id;

                    if (cart[id].qty >= cart[id].stock) {
                        alert('Stok tidak mencukupi.');
                        return;
                    }

                    cart[id].qty++;
                    renderCart();
                }

                if (e.target.classList.contains('decrease-item')) {
                    const id = e.target.dataset.id;

                    cart[id].qty--;

                    if (cart[id].qty <= 0) {
                        delete cart[id];
                    }

                    renderCart();
                }

                if (e.target.classList.contains('remove-item')) {
                    delete cart[e.target.dataset.id];
                    renderCart();
                }
            });

            uangDibayar.addEventListener('input', updateKembalian);

            form.addEventListener('submit', function(e) {
                if (Object.keys(cart).length === 0) {
                    e.preventDefault();
                    alert('Pilih produk terlebih dahulu.');
                }
            });

            const searchProduct = document.getElementById('searchProduct');
            const productCards = document.querySelectorAll('.product-card');

            searchProduct.addEventListener('input', function() {
                const keyword = this.value.toLowerCase().trim();

                productCards.forEach(card => {
                    const data = card.dataset.search || '';
                    card.style.display = data.includes(keyword) ? '' : 'none';
                });
            });

            const searchTransaction = document.getElementById('searchTransaction');
            const transactionRows = document.querySelectorAll('.transaction-row');
            const emptySearchRow = document.getElementById('emptySearchRow');

            searchTransaction.addEventListener('input', function() {
                const keyword = this.value.toLowerCase().trim();
                let visibleCount = 0;

                transactionRows.forEach(row => {
                    const data = row.dataset.search || '';

                    if (data.includes(keyword)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (emptySearchRow) {
                    emptySearchRow.classList.toggle('hidden', visibleCount > 0);
                }
            });
        });
    </script>
</x-app-layout>
