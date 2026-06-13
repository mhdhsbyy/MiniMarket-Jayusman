<x-guest-layout>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-900 via-slate-950 to-green-950 px-4 py-10">
        <div
            class="w-full max-w-5xl grid md:grid-cols-2 bg-white/10 backdrop-blur-xl rounded-3xl overflow-hidden shadow-2xl border border-white/20">

            <!-- Bagian Kiri -->
            <div
                class="hidden md:flex flex-col justify-between p-10 bg-gradient-to-br from-emerald-600 to-green-800 text-white relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-60 h-60 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-yellow-300/20 rounded-full"></div>

                <div class="relative z-10 flex">
                    <div
                        class="w-24 h-24 bg-white/15 backdrop-blur-md rounded-3xl flex items-center justify-center border border-white/20 shadow-2xl mb-6  mr-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1 5h12M10 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" />
                        </svg>
                    </div>

                    <div class="mt-2">
                        <h1 class="text-5xl font-extrabold tracking-tight">
                        JAYUSMART
                    </h1>

                    <p class="text-emerald-100 mt-2 text-lg">
                        Smart Retail Management System
                    </p>
                    </div>

                </div>

                <div class="relative z-10 text-sm text-emerald-100">
                    © {{ date('Y') }} Mini Market Jayusman
                </div>
            </div>

            <!-- Bagian Kanan -->
            <div class="bg-white p-8 md:p-12">
                <div class="md:hidden flex justify-center mb-6">
                    <div class="w-20 h-20 bg-emerald-100 rounded-2xl flex items-center justify-center shadow">
                        <span class="text-4xl">🛒</span>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-slate-800">
                        Selamat Datang
                    </h2>
                    <p class="text-slate-500 mt-2">
                        Silakan login untuk masuk ke sistem.
                    </p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-semibold text-slate-700 mb-2">
                            Username
                        </label>

                        <input id="username" type="text" name="username" value="{{ old('username') }}" required
                            autofocus autocomplete="username" placeholder="Masukkan username"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-700 focus:border-emerald-500 focus:ring-emerald-500">

                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                            Password
                        </label>

                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-700 focus:border-emerald-500 focus:ring-emerald-500">

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                            <span class="ms-2 text-sm text-slate-600">
                                Ingat saya
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-emerald-700 hover:text-emerald-900 font-medium">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl shadow-lg shadow-emerald-600/30 transition duration-300">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
