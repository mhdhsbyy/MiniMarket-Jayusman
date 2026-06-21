<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-[#07150f]/95 backdrop-blur-xl border-b border-white/10">
    <div class="max-w-7xl mx-auto px-6 py-[10px]">
        <div class="h-20 flex items-center justify-between">

            {{-- Brand --}}
            <a href="{{ route('warehouse.dashboard') }}" class="flex items-center gap-3">
                <div>
                    <h1 class="text-xl font-black tracking-tight text-white">
                        JAYUSMART
                    </h1>

                    <p class="text-xs text-emerald-300 font-medium">
                        Warehouse Panel - {{ Auth::user()->branch->nama ?? 'Cabang' }}
                    </p>
                </div>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-2">

                <a href="{{ route('warehouse.dashboard') }}"
                    class="px-5 py-2.5 rounded-full text-sm font-bold transition
                    {{ request()->routeIs('warehouse.dashboard')
                        ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30'
                        : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                    Dashboard
                </a>

                <a href="{{ route('warehouse.stocks.index') }}"
                    class="px-5 py-2.5 rounded-full text-sm font-bold transition
                    {{ request()->routeIs('warehouse.stocks.*')
                        ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30'
                        : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                    Stok
                </a>

                <a href="{{ route('warehouse.incoming-goods.index') }}"
                    class="px-5 py-2.5 rounded-full text-sm font-bold transition
                    {{ request()->routeIs('warehouse.incoming-goods.*')
                        ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30'
                        : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                    Barang Masuk
                </a>

            </div>

            {{-- Account Dropdown --}}
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

                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
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
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Logout
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Mobile Button --}}
            <button @click="open = ! open"
                class="md:hidden w-11 h-11 rounded-full bg-white/10 border border-white/10 text-white flex items-center justify-center">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />

                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>
    </div>

    {{-- Mobile Menu --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden bg-[#07150f] border-t border-white/10">

        <div class="px-6 py-5 space-y-2">

            <a href="{{ route('warehouse.dashboard') }}"
                class="block px-5 py-3 rounded-2xl text-sm font-bold
                {{ request()->routeIs('warehouse.dashboard')
                    ? 'bg-emerald-500 text-white'
                    : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                Dashboard
            </a>

            <p class="px-5 pt-4 text-xs font-black text-emerald-300 uppercase tracking-widest">
                Operasional
            </p>

            <a href="{{ route('warehouse.stocks.index') }}"
                class="block px-5 py-3 rounded-2xl text-sm font-bold
                {{ request()->routeIs('warehouse.stocks.*')
                    ? 'bg-emerald-500 text-white'
                    : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                Stok
            </a>

            <a href="{{ route('warehouse.incoming-goods.index') }}"
                class="block px-5 py-3 rounded-2xl text-sm font-bold
                {{ request()->routeIs('warehouse.incoming-goods.*')
                    ? 'bg-emerald-500 text-white'
                    : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                Barang Masuk
            </a>

        </div>

        <div class="px-6 py-5 border-t border-white/10">
            <p class="text-sm font-bold text-white">
                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
            </p>

            <p class="text-xs text-emerald-300 mb-4">
                Warehouse
            </p>

            <a href="{{ route('profile.edit') }}"
                class="block px-5 py-3 rounded-2xl text-sm font-bold text-slate-300 hover:bg-white/10 hover:text-white">
                Profile
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    class="block px-5 py-3 rounded-2xl text-sm font-bold text-red-400 hover:bg-red-500/10">
                    Log Out
                </a>
            </form>
        </div>
    </div>
</nav>
