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

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Cashier Panel
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Transaksi Baru
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Pilih produk dari daftar, masukkan pembayaran, lalu simpan transaksi pelanggan.
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
                            Kasir Cabang
                        </p>
                    </div>
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

            <form method="POST" action="{{ route('cashier.transactions.store') }}" id="transactionForm">
                @csrf

                <input type="hidden" name="print_receipt" id="printReceipt" value="0">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-black text-slate-900">
                                Daftar Produk
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                Cari dan filter produk berdasarkan kategori.
                            </p>

                            <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-3">
                                <input type="text" id="searchProduct"
                                    placeholder="Cari produk pada halaman ini..."
                                    autocomplete="off"
                                    class="md:col-span-2 w-full rounded-2xl border-slate-200 px-5 py-4 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">

                                <select id="filterCategory"
                                    class="w-full rounded-2xl border-slate-200 px-5 py-4 text-sm font-bold focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ strtolower($category->nama) }}">
                                            {{ $category->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50 border-b border-slate-200">
                                    <tr>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase w-12">No</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kode</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Produk</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Kategori</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Harga</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase">Stok</th>
                                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase text-right">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($products as $product)
                                        @php
                                            $stock = $product->stocks
                                                ->where('branch_id', Auth::user()->branch_id)
                                                ->first();

                                            $jumlahStok = $stock->jumlah_stok ?? 0;
                                        @endphp

                                        <tr class="product-row hover:bg-slate-50 transition"
                                            data-category="{{ strtolower($product->category->nama ?? '') }}"
                                            data-search="{{ strtolower($product->kode . ' ' . $product->nama . ' ' . ($product->category->nama ?? '') . ' ' . ($product->supplier->nama ?? '')) }}">

                                            <td class="px-6 py-5 text-sm font-black text-slate-400 text-center">
                                                {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="px-6 py-5 font-black text-slate-900">
                                                {{ $product->kode }}
                                            </td>

                                            <td class="px-6 py-5">
                                                <p class="font-black text-slate-900">
                                                    {{ $product->nama }}
                                                </p>
                                                <p class="text-sm text-slate-500">
                                                    {{ $product->supplier->nama ?? '-' }}
                                                    @if ($product->supplier && $product->supplier->status === 'inactive')
                                                        <span class="ml-2 px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-black">Tidak restock</span>
                                                    @endif
                                                </p>
                                            </td>

                                            <td class="px-6 py-5 text-sm font-bold text-slate-600">
                                                {{ $product->category->nama ?? '-' }}
                                            </td>

                                            <td class="px-6 py-5 font-black text-emerald-700">
                                                Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                                            </td>

                                            <td class="px-6 py-5">
                                                @if ($jumlahStok <= 0)
                                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-black">
                                                        Habis
                                                    </span>
                                                @elseif ($jumlahStok < 30)
                                                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-black">
                                                        {{ $jumlahStok }}
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-black">
                                                        {{ $jumlahStok }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-6 py-5 text-right">
                                                <button type="button"
                                                    class="add-product px-4 py-2 rounded-xl bg-emerald-100 text-emerald-700 text-sm font-black hover:bg-emerald-200 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                    data-id="{{ $product->id }}"
                                                    data-name="{{ $product->nama }}"
                                                    data-price="{{ $product->harga_jual }}"
                                                    data-stock="{{ $jumlahStok }}"
                                                    @disabled($jumlahStok <= 0)>
                                                    Tambah
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-16 text-center text-slate-500 font-bold">
                                                Tidak ada produk tersedia.
                                            </td>
                                        </tr>
                                    @endforelse

                                    <tr id="emptyProductSearch" class="hidden">
                                        <td colspan="7" class="px-6 py-16 text-center text-slate-500 font-bold">
                                            Produk tidak ditemukan pada halaman ini.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="p-6 border-t border-slate-200">
                            {{ $products->links() }}
                        </div>
                    </div>

                    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden h-fit sticky top-28">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-black text-slate-900">
                                Keranjang
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                Ringkasan transaksi pelanggan.
                            </p>
                        </div>

                        <div class="p-6 space-y-5">
                            <div id="cartItems" class="space-y-3 max-h-[320px] overflow-y-auto">
                                <div class="py-12 text-center text-slate-400 font-bold">
                                    Belum ada produk dipilih.
                                </div>
                            </div>

                            <div class="border-t border-slate-200 pt-5 space-y-4">
                                <div class="bg-emerald-50 border border-emerald-100 rounded-[1.5rem] p-5">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-sm font-black text-slate-500 uppercase">
                                            Total Bayar
                                        </p>

                                        <p id="totalText" class="text-2xl font-black text-emerald-700">
                                            Rp 0
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-sm font-black text-slate-700">
                                        Uang Dibayar
                                    </label>

                                    <input type="text" id="uangDibayarDisplay"
                                        placeholder="Rp 0"
                                        autocomplete="off"
                                        class="mt-2 w-full rounded-2xl border-slate-200 px-5 py-4 text-lg font-black focus:border-emerald-500 focus:ring-emerald-500">

                                    <input type="hidden" name="uang_dibayar" id="uangDibayar" required>

                                    <button type="button" id="payExact"
                                        class="mt-3 w-full py-3 rounded-2xl bg-emerald-100 text-emerald-700 text-sm font-black hover:bg-emerald-200 transition">
                                        Uang Pas
                                    </button>
                                </div>

                                <div class="flex items-center justify-between p-5 rounded-[1.5rem] bg-slate-50 border border-slate-100">
                                    <p class="font-black text-slate-500 uppercase text-sm">
                                        Kembalian
                                    </p>

                                    <p id="kembalianText" class="text-2xl font-black text-emerald-700">
                                        Rp 0
                                    </p>
                                </div>

                                <button type="submit"
                                    class="w-full py-5 rounded-2xl bg-emerald-700 text-white font-black hover:bg-emerald-800 transition">
                                    Simpan Transaksi
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- MODAL KONFIRMASI --}}
    <div id="confirmModal"
        class="fixed inset-0 z-[999] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">

        <div class="w-full max-w-md bg-white rounded-[2rem] shadow-2xl overflow-hidden">
            <div class="p-8 text-center">

                <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-10 h-10 text-emerald-700"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                    Konfirmasi
                </p>

                <h3 class="text-2xl font-black text-slate-900 mt-2">
                    Simpan Transaksi?
                </h3>

                <p class="text-slate-500 mt-3">
                    Pilih simpan biasa atau simpan sekaligus cetak struk.
                </p>

                <div class="mt-6 rounded-[1.5rem] bg-slate-50 border border-slate-100 p-5 space-y-3 text-left">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-black text-slate-500 uppercase">
                            Total Bayar
                        </span>
                        <span id="modalTotalText" class="text-lg font-black text-emerald-700">
                            Rp 0
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-black text-slate-500 uppercase">
                            Uang Dibayar
                        </span>
                        <span id="modalUangText" class="text-lg font-black text-slate-900">
                            Rp 0
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-black text-slate-500 uppercase">
                            Kembalian
                        </span>
                        <span id="modalKembalianText" class="text-lg font-black text-slate-900">
                            Rp 0
                        </span>
                    </div>
                </div>

                <div class="mt-8 space-y-3">
                    <button type="button"
                        id="confirmPrintSubmit"
                        class="w-full py-4 rounded-2xl bg-emerald-700 text-white font-black hover:bg-emerald-800 transition">
                        Simpan & Cetak Struk
                    </button>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button"
                            id="cancelSubmit"
                            class="py-4 rounded-2xl bg-slate-100 text-slate-700 font-black hover:bg-slate-200 transition">
                            Batal
                        </button>

                        <button type="button"
                            id="confirmSubmit"
                            class="py-4 rounded-2xl bg-emerald-100 text-emerald-700 font-black hover:bg-emerald-200 transition">
                            Simpan Saja
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formatRupiah = number => {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            };

            const cartItems = document.getElementById('cartItems');
            const totalText = document.getElementById('totalText');
            const uangDibayar = document.getElementById('uangDibayar');
            const uangDibayarDisplay = document.getElementById('uangDibayarDisplay');
            const form = document.getElementById('transactionForm');
            const payExact = document.getElementById('payExact');
            const kembalianText = document.getElementById('kembalianText');

            const confirmModal = document.getElementById('confirmModal');
            const confirmSubmit = document.getElementById('confirmSubmit');
            const confirmPrintSubmit = document.getElementById('confirmPrintSubmit');
            const cancelSubmit = document.getElementById('cancelSubmit');
            const printReceipt = document.getElementById('printReceipt');

            const modalTotalText = document.getElementById('modalTotalText');
            const modalUangText = document.getElementById('modalUangText');
            const modalKembalianText = document.getElementById('modalKembalianText');

            let cart = {};
            let confirmedSubmit = false;

            function getTotal() {
                return Object.values(cart).reduce((total, item) => total + (item.price * item.qty), 0);
            }

            function getBayar() {
                return parseInt(uangDibayar.value || 0);
            }

            function getKembalian() {
                const kembalian = getBayar() - getTotal();
                return kembalian > 0 ? kembalian : 0;
            }

            function updateKembalian() {
                kembalianText.innerText = formatRupiah(getKembalian());
            }

            function setUangDibayar(value) {
                const angka = parseInt(value || 0);

                uangDibayar.value = angka > 0 ? angka : 0;
                uangDibayarDisplay.value = angka > 0 ? formatRupiah(angka) : '';

                updateKembalian();
            }

            function openConfirmModal() {
                modalTotalText.innerText = formatRupiah(getTotal());
                modalUangText.innerText = formatRupiah(getBayar());
                modalKembalianText.innerText = formatRupiah(getKembalian());

                confirmModal.classList.remove('hidden');
                confirmModal.classList.add('flex');
            }

            function closeConfirmModal() {
                confirmModal.classList.add('hidden');
                confirmModal.classList.remove('flex');
            }

            function submitTransaction(print = false) {
                confirmedSubmit = true;
                printReceipt.value = print ? 1 : 0;
                closeConfirmModal();
                form.submit();
            }

            function renderCart() {
                cartItems.innerHTML = '';
                let index = 0;

                Object.values(cart).forEach(item => {
                    cartItems.innerHTML += `
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <input type="hidden" name="products[${index}][product_id]" value="${item.id}">
                            <input type="hidden" name="products[${index}][jumlah]" value="${item.qty}">

                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-black text-slate-900 truncate">${item.name}</p>
                                    <p class="text-sm text-slate-500 font-bold">
                                        ${formatRupiah(item.price)} x ${item.qty}
                                    </p>
                                </div>

                                <button type="button"
                                    class="remove-item text-red-600 text-sm font-black"
                                    data-id="${item.id}">
                                    Hapus
                                </button>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                        class="decrease-item w-9 h-9 rounded-xl bg-white border border-slate-200 font-black"
                                        data-id="${item.id}">
                                        -
                                    </button>

                                    <span class="font-black text-slate-900 w-8 text-center">
                                        ${item.qty}
                                    </span>

                                    <button type="button"
                                        class="increase-item w-9 h-9 rounded-xl bg-white border border-slate-200 font-black"
                                        data-id="${item.id}">
                                        +
                                    </button>
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
                        <div class="py-12 text-center text-slate-400 font-bold">
                            Belum ada produk dipilih.
                        </div>
                    `;

                    setUangDibayar(0);
                }

                totalText.innerText = formatRupiah(getTotal());
                updateKembalian();
            }

            document.querySelectorAll('.add-product').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const price = parseInt(this.dataset.price);
                    const stock = parseInt(this.dataset.stock);

                    if (stock <= 0) {
                        alert('Stok produk habis.');
                        return;
                    }

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

            uangDibayarDisplay.addEventListener('input', function() {
                let angka = this.value.replace(/\D/g, '');

                if (!angka) {
                    angka = 0;
                }

                uangDibayar.value = angka;
                this.value = angka > 0 ? formatRupiah(angka) : '';

                updateKembalian();
            });

            payExact.addEventListener('click', function() {
                if (Object.keys(cart).length === 0) {
                    setUangDibayar(0);
                    alert('Pilih produk terlebih dahulu.');
                    return;
                }

                setUangDibayar(getTotal());
            });

            form.addEventListener('submit', function(e) {
                if (confirmedSubmit) {
                    return;
                }

                if (Object.keys(cart).length === 0) {
                    e.preventDefault();
                    alert('Pilih produk terlebih dahulu.');
                    return;
                }

                if (getBayar() < getTotal()) {
                    e.preventDefault();
                    alert('Uang dibayar kurang dari total transaksi.');
                    return;
                }

                e.preventDefault();
                openConfirmModal();
            });

            cancelSubmit.addEventListener('click', closeConfirmModal);

            confirmSubmit.addEventListener('click', function() {
                submitTransaction(false);
            });

            confirmPrintSubmit.addEventListener('click', function() {
                submitTransaction(true);
            });

            confirmModal.addEventListener('click', function(e) {
                if (e.target === confirmModal) {
                    closeConfirmModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeConfirmModal();
                }
            });

            const searchProduct = document.getElementById('searchProduct');
            const filterCategory = document.getElementById('filterCategory');
            const productRows = document.querySelectorAll('.product-row');
            const emptyProductSearch = document.getElementById('emptyProductSearch');

            function filterProducts() {
                const keyword = searchProduct.value.toLowerCase().trim();
                const category = filterCategory.value.toLowerCase().trim();

                let visibleCount = 0;

                productRows.forEach(row => {
                    const data = row.dataset.search || '';
                    const rowCategory = row.dataset.category || '';

                    const matchSearch = data.includes(keyword);
                    const matchCategory = category === '' || rowCategory === category;

                    if (matchSearch && matchCategory) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (emptyProductSearch) {
                    emptyProductSearch.classList.toggle('hidden', visibleCount > 0);
                }
            }

            searchProduct.addEventListener('input', filterProducts);
            filterCategory.addEventListener('change', filterProducts);
        });
    </script>
</x-app-layout>
