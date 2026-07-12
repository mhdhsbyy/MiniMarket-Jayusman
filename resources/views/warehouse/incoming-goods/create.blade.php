<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-5xl mx-auto px-6 py-8 space-y-8">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase tracking-widest">
                        Warehouse Panel
                    </p>

                    <h1 class="text-4xl font-black text-slate-900 mt-3">
                        Tambah Barang Masuk
                    </h1>

                    <p class="text-slate-500 mt-3">
                        Input barang masuk dan stok cabang akan otomatis bertambah.
                    </p>
                </div>

                <a href="{{ route('warehouse.incoming-goods.index') }}"
                    class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-700 font-black hover:bg-slate-200 transition text-center">
                    Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-700 px-6 py-4 rounded-2xl">
                    <ul class="list-disc list-inside font-bold space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-black text-slate-900">
                        Form Barang Masuk
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Cari produk berdasarkan nama, kode, kategori, atau supplier.
                    </p>
                </div>

                <form form id="incomingGoodForm" method="POST" action="{{ route('warehouse.incoming-goods.store') }}"
                    class="p-6 space-y-6">
                    @csrf

                    <input type="hidden" name="product_id" id="product_id" value="{{ old('product_id') }}">
                    <input type="hidden" name="harga_beli" id="harga_beli" value="{{ old('harga_beli') }}">
                    <input type="hidden" name="tanggal_masuk" id="tanggal_masuk"
                        value="{{ old('tanggal_masuk', now()) }}">

                    {{-- Cari Produk --}}
                    <div class="space-y-3">
                        <label class="block text-sm font-black text-slate-700">
                            Cari Produk
                        </label>

                        <div class="relative">
                            <input type="text" id="productSearch" autocomplete="off"
                                placeholder="Ketik nama atau kode produk..."
                                class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-600 focus:border-emerald-500 focus:ring-emerald-500 pr-12">

                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                                🔍
                            </div>
                        </div>

                        <div id="productList"
                            class="hidden border border-slate-200 rounded-2xl overflow-hidden bg-white shadow-sm max-h-72 overflow-y-auto">
                            @foreach ($products as $product)
                                <button type="button"
                                    class="product-item w-full text-left px-5 py-4 hover:bg-emerald-50 transition border-b border-slate-100 last:border-b-0"
                                    data-id="{{ $product->id }}" data-name="{{ $product->nama }}"
                                    data-code="{{ $product->kode }}"
                                    data-category="{{ $product->category->nama ?? '-' }}"
                                    data-supplier="{{ $product->supplier->nama ?? '-' }}"
                                    data-harga="{{ (int) $product->harga_beli }}"
                                    data-search="{{ strtolower($product->kode . ' ' . $product->nama . ' ' . ($product->category->nama ?? '') . ' ' . ($product->supplier->nama ?? '')) }}">

                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                        <div>
                                            <p class="font-black text-slate-900">
                                                {{ $product->nama }}
                                            </p>

                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ $product->kode }} • {{ $product->category->nama ?? '-' }}
                                            </p>
                                        </div>

                                        <div class="text-left md:text-right">
                                            <p class="text-sm font-black text-emerald-700">
                                                Rp {{ number_format($product->harga_beli, 0, ',', '.') }}
                                            </p>

                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ $product->supplier->nama ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </button>
                            @endforeach

                            <div id="emptyProductResult" class="hidden px-5 py-8 text-center text-slate-500">
                                Produk tidak ditemukan.
                            </div>
                        </div>

                        @error('product_id')
                            <p class="text-sm font-bold text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Produk Terpilih --}}
                    <div id="selectedProductCard"
                        class="hidden rounded-[1.5rem] border border-emerald-100 bg-emerald-50 p-5">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div>
                                <p class="text-xs font-black text-emerald-700 uppercase tracking-widest">
                                    Produk Terpilih
                                </p>

                                <h3 id="selectedProductName" class="text-xl font-black text-slate-900 mt-2">
                                    -
                                </h3>

                                <p id="selectedProductMeta" class="text-sm text-emerald-700 mt-1">
                                    -
                                </p>
                            </div>

                            <button type="button" id="changeProductButton"
                                class="px-5 py-3 rounded-2xl bg-white text-slate-700 text-sm font-black hover:bg-slate-50 transition border border-emerald-100">
                                Ganti Produk
                            </button>
                        </div>
                    </div>

                    {{-- Detail Input --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Jumlah Masuk
                            </label>

                            <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="1" required
                                placeholder="Contoh: 20"
                                class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-600 focus:border-emerald-500 focus:ring-emerald-500">

                            @error('jumlah')
                                <p class="text-sm font-bold text-red-600 mt-2">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Harga Beli
                            </label>

                            <input type="text" id="harga_beli_display"
                                value="{{ old('harga_beli') ? 'Rp ' . number_format(old('harga_beli'), 0, ',', '.') : '' }}"
                                required placeholder="Rp 0"
                                class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-600 focus:border-emerald-500 focus:ring-emerald-500">

                            @error('harga_beli')
                                <p class="text-sm font-bold text-red-600 mt-2">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-2">
                                Tanggal Masuk
                            </label>

                            <div class="flex gap-3">
                                <div class="relative flex-1">
                                    <input type="text" id="tanggal_masuk_display" readonly
                                        class="w-full rounded-2xl border-slate-200 text-sm font-bold text-slate-600 focus:border-emerald-500 focus:ring-emerald-500 cursor-pointer">

                                    <input type="date" id="tanggal_masuk_picker"
                                        value="{{ old('tanggal_masuk') ? \Carbon\Carbon::parse(old('tanggal_masuk'))->toDateString() : now()->toDateString() }}"
                                        class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>

                                <input type="time" id="tanggal_masuk_time"
                                    value="{{ old('tanggal_masuk') ? \Carbon\Carbon::parse(old('tanggal_masuk'))->format('H:i') : now()->format('H:i') }}"
                                    class="w-32 rounded-2xl border-slate-200 text-sm font-bold text-slate-600 focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            @error('tanggal_masuk')
                                <p class="text-sm font-bold text-red-600 mt-2">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <div class="rounded-[1.5rem] bg-slate-50 border border-slate-200 p-5">
                        <p class="font-black text-slate-800">
                            Catatan
                        </p>

                        <p class="text-sm text-slate-500 mt-1">
                            Setelah disimpan, stok produk pada cabang ini akan otomatis bertambah sesuai jumlah barang
                            masuk.
                        </p>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 pt-4 border-t border-slate-200">
                        <a href="{{ route('warehouse.incoming-goods.index') }}"
                            class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-700 font-black hover:bg-slate-200 transition text-center">
                            Batal
                        </a>

                        <button type="button" id="openConfirmModal"
                            class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black hover:bg-emerald-800 transition shadow-lg shadow-emerald-900/20">
                            Simpan Barang Masuk
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
    <div id="confirmModal"
        class="fixed inset-0 z-[999] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">

        <div class="w-full max-w-md bg-white rounded-[2rem] shadow-2xl border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-2xl font-black text-slate-900">
                    Simpan Barang Masuk?
                </h2>

                <p class="text-sm text-slate-500 mt-2">
                    Pastikan produk, jumlah, harga beli, dan tanggal sudah benar. Setelah disimpan, stok akan otomatis
                    bertambah.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-emerald-50 m-6 border border-emerald-100">
                <p class="text-sm font-black text-emerald-700 uppercase tracking-widest">
                    Konfirmasi
                </p>

                <p class="text-slate-700 mt-2 font-bold">
                    Data barang masuk akan masuk ke riwayat dan tidak disediakan fitur edit agar histori stok tetap
                    aman.
                </p>
            </div>

            <div class="p-6 pt-0 flex flex-col sm:flex-row sm:justify-end gap-3">
                <button type="button" id="cancelConfirmModal"
                    class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-700 font-black hover:bg-slate-200 transition">
                    Batal
                </button>

                <button type="button" id="submitIncomingGood"
                    class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-black hover:bg-emerald-800 transition shadow-lg shadow-emerald-900/20">
                    Ya, Simpan
                </button>
            </div>
        </div>
    </div>
    <script>
        const oldProductId = "{{ old('product_id') }}";

        const productSearch = document.getElementById('productSearch');
        const productList = document.getElementById('productList');
        const productItems = document.querySelectorAll('.product-item');
        const emptyProductResult = document.getElementById('emptyProductResult');

        const productIdInput = document.getElementById('product_id');
        const hargaBeliInput = document.getElementById('harga_beli');
        const hargaBeliDisplay = document.getElementById('harga_beli_display');

        const tanggalMasukInput = document.getElementById('tanggal_masuk');
        const tanggalMasukDisplay = document.getElementById('tanggal_masuk_display');
        const tanggalMasukPicker = document.getElementById('tanggal_masuk_picker');
        const tanggalMasukTime = document.getElementById('tanggal_masuk_time');

        const selectedProductCard = document.getElementById('selectedProductCard');
        const selectedProductName = document.getElementById('selectedProductName');
        const selectedProductMeta = document.getElementById('selectedProductMeta');
        const changeProductButton = document.getElementById('changeProductButton');

        const incomingGoodForm = document.getElementById('incomingGoodForm');
        const openConfirmModal = document.getElementById('openConfirmModal');
        const confirmModal = document.getElementById('confirmModal');
        const cancelConfirmModal = document.getElementById('cancelConfirmModal');
        const submitIncomingGood = document.getElementById('submitIncomingGood');

        openConfirmModal?.addEventListener('click', function() {
            if (!incomingGoodForm.checkValidity()) {
                incomingGoodForm.reportValidity();
                return;
            }

            confirmModal.classList.remove('hidden');
            confirmModal.classList.add('flex');
        });

        cancelConfirmModal?.addEventListener('click', function() {
            confirmModal.classList.add('hidden');
            confirmModal.classList.remove('flex');
        });

        submitIncomingGood?.addEventListener('click', function() {
            incomingGoodForm.submit();
        });

        function onlyNumber(value) {
            return value.replace(/[^\d]/g, '');
        }

        function formatRupiah(number) {
            number = parseInt(number || 0);
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function formatTanggalIndonesia(dateString, timeString) {
            if (!dateString) return '';

            const date = new Date(dateString + 'T' + (timeString || '00:00') + ':00');

            return new Intl.DateTimeFormat('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(date);
        }

        function updateTanggalDisplay() {
            const date = tanggalMasukPicker.value;
            const time = tanggalMasukTime.value || '00:00';
            tanggalMasukInput.value = date + ' ' + time + ':00';
            tanggalMasukDisplay.value = formatTanggalIndonesia(date, time);
        }

        function showProductList() {
            productList.classList.remove('hidden');
        }

        function hideProductList() {
            productList.classList.add('hidden');
        }

        function filterProducts() {
            const keyword = productSearch.value.toLowerCase().trim();
            let visibleCount = 0;

            showProductList();

            productItems.forEach(item => {
                const searchText = item.dataset.search || '';
                const match = searchText.includes(keyword);

                if (match) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            emptyProductResult.classList.toggle('hidden', visibleCount !== 0);
        }

        function selectProductFromElement(item) {
            const product = {
                id: item.dataset.id,
                name: item.dataset.name,
                code: item.dataset.code,
                category: item.dataset.category,
                supplier: item.dataset.supplier,
                harga: item.dataset.harga
            };

            productIdInput.value = product.id;

            hargaBeliInput.value = parseInt(product.harga || 0);
            hargaBeliDisplay.value = formatRupiah(product.harga);

            productSearch.value = `${product.code} - ${product.name}`;

            selectedProductName.textContent = product.name;
            selectedProductMeta.textContent =
                `${product.code} • ${product.category} • ${product.supplier} • ${formatRupiah(product.harga)}`;

            selectedProductCard.classList.remove('hidden');

            hideProductList();
        }

        productSearch?.addEventListener('focus', filterProducts);

        productSearch?.addEventListener('input', function() {
            productIdInput.value = '';
            selectedProductCard.classList.add('hidden');
            filterProducts();
        });

        productItems.forEach(item => {
            item.addEventListener('click', function() {
                selectProductFromElement(this);
            });
        });

        changeProductButton?.addEventListener('click', function() {
            productSearch.focus();
            filterProducts();
        });

        hargaBeliDisplay?.addEventListener('input', function() {
            const rawValue = onlyNumber(this.value);

            hargaBeliInput.value = rawValue;
            this.value = rawValue ? formatRupiah(rawValue) : '';
        });

        tanggalMasukPicker?.addEventListener('change', updateTanggalDisplay);
        tanggalMasukTime?.addEventListener('change', updateTanggalDisplay);

        document.addEventListener('click', function(event) {
            const isClickInside =
                productSearch.contains(event.target) ||
                productList.contains(event.target);

            if (!isClickInside) {
                hideProductList();
            }
        });

        if (oldProductId) {
            productItems.forEach(item => {
                if (String(item.dataset.id) === String(oldProductId)) {
                    selectProductFromElement(item);
                }
            });
        }

        updateTanggalDisplay();
    </script>

</x-app-layout>
