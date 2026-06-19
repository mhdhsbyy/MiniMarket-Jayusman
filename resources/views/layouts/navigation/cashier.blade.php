<nav class="bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-20">

            <div class="flex items-center gap-8">
                <a href="{{ route('cashier.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-700 flex items-center justify-center text-white font-black">
                        C
                    </div>

                    <div>
                        <h1 class="font-black text-slate-900 leading-tight">
                            Cashier Panel
                        </h1>
                        <p class="text-xs text-slate-500 font-bold">
                            Mini Market Jayusman
                        </p>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-2">
                    <a href="{{ route('cashier.dashboard') }}"
                        class="px-4 py-2 rounded-2xl text-sm font-black transition
                        {{ request()->routeIs('cashier.dashboard') ? 'bg-emerald-100 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('cashier.transactions.index') }}"
                        class="px-4 py-2 rounded-2xl text-sm font-black transition
                        {{ request()->routeIs('cashier.transactions.*') ? 'bg-emerald-100 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
                        Transaksi
                    </a>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-black text-slate-900">
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </p>
                    <p class="text-xs text-slate-500 font-bold">
                        {{ Auth::user()->branch->nama ?? 'Cabang' }}
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="px-4 py-2 rounded-2xl bg-red-50 text-red-700 text-sm font-black hover:bg-red-100 transition">
                        Logout
                    </button>
                </form>
            </div>

            <div class="md:hidden flex items-center">
                <button type="button" onclick="document.getElementById('cashierMobileMenu').classList.toggle('hidden')"
                    class="p-2 rounded-xl text-slate-600 hover:bg-slate-100">
                    ☰
                </button>
            </div>
        </div>
    </div>

    <div id="cashierMobileMenu" class="hidden md:hidden border-t border-slate-200 px-6 py-4 space-y-2">
        <a href="{{ route('cashier.dashboard') }}"
            class="block px-4 py-3 rounded-2xl text-sm font-black
            {{ request()->routeIs('cashier.dashboard') ? 'bg-emerald-100 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Dashboard
        </a>

        <a href="{{ route('cashier.transactions.index') }}"
            class="block px-4 py-3 rounded-2xl text-sm font-black
            {{ request()->routeIs('cashier.transactions.*') ? 'bg-emerald-100 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Transaksi
        </a>

        <div class="pt-4 border-t border-slate-200">
            <p class="text-sm font-black text-slate-900">
                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
            </p>

            <p class="text-xs text-slate-500 font-bold mt-1">
                {{ Auth::user()->branch->nama ?? 'Cabang' }}
            </p>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf

                <button type="submit"
                    class="w-full px-4 py-3 rounded-2xl bg-red-50 text-red-700 text-sm font-black hover:bg-red-100 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>
