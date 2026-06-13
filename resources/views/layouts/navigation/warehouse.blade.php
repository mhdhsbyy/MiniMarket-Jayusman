<nav x-data="{ open: false, barangOpen: false }"
    class="sticky top-0 z-50 bg-[#07150f]/95 backdrop-blur-xl border-b border-white/10">

    <div class="max-w-7xl mx-auto px-6">
        <div class="h-20 flex items-center justify-between">

            <!-- Brand -->
            <a href="{{ route('warehouse.dashboard') }}" class="flex items-center gap-3">
                <div>
                    <h1 class="text-xl font-black tracking-tight text-white">
                        JAYUSMART
                    </h1>

                    <p class="text-xs text-emerald-300 font-medium">
                        Warehouse Panel
                    </p>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center gap-2">

                <a href="{{ route('warehouse.dashboard') }}"
                    class="px-5 py-3 rounded-2xl text-sm font-bold transition
                    {{ request()->routeIs('warehouse.dashboard')
                        ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30'
                        : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                    Dashboard
                </a>

                <!-- Manajemen Barang -->
                <div class="relative" x-data="{ open: false }">

                    <button @click="open = ! open"
                        class="px-5 py-3 rounded-2xl text-sm font-bold flex items-center gap-2 transition
                        {{
                            request()->routeIs('warehouse.stocks.*') ||
                            request()->routeIs('warehouse.incoming-goods.*') ||
                            request()->routeIs('warehouse.outgoing-goods.*')
                            ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/30'
                            : 'text-slate-300 hover:text-white hover:bg-white/5'
                        }}">
                        Manajemen Barang

                        <svg class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                        x-transition
                        @click.away="open = false"
                        class="absolute left-0 mt-3 w-60 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50">

                        <a href="{{ route('warehouse.stocks.index') }}"
                            class="block px-5 py-3 text-sm font-bold transition
                            {{ request()->routeIs('warehouse.stocks.*')
                                ? 'bg-emerald-50 text-emerald-700'
                                : 'text-slate-700 hover:bg-slate-50' }}">
                            Stok Barang
                        </a>

                        <a href="{{ route('warehouse.incoming-goods.index') }}"
                            class="block px-5 py-3 text-sm font-bold transition
                            {{ request()->routeIs('warehouse.incoming-goods.*')
                                ? 'bg-emerald-50 text-emerald-700'
                                : 'text-slate-700 hover:bg-slate-50' }}">
                            Barang Masuk
                        </a>

                        <a href="#"
                            class="block px-5 py-3 text-sm font-bold transition
                            {{ request()->routeIs('warehouse.outgoing-goods.*')
                                ? 'bg-emerald-50 text-emerald-700'
                                : 'text-slate-700 hover:bg-slate-50' }}">
                            Barang Keluar
                        </a>

                    </div>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="hidden md:flex items-center">

                <x-dropdown align="right" width="60">

                    <x-slot name="trigger">
                        <button
                            class="flex items-center gap-3 px-4 py-2 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition">

                            <div class="text-right">
                                <p class="text-sm font-bold text-white">
                                    {{ Auth::user()->first_name }}
                                </p>

                                <p class="text-xs text-emerald-300">
                                    Warehouse
                                </p>
                            </div>

                            <svg class="w-4 h-4 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"/>
                            </svg>

                        </button>
                    </x-slot>

                    <x-slot name="content">

                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                this.closest('form').submit();">
                                Logout
                            </x-dropdown-link>
                        </form>

                    </x-slot>

                </x-dropdown>

            </div>

        </div>
    </div>
</nav>
