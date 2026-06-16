<x-app-layout>
    <div class="min-h-screen bg-[#f4f7f5]">
        <div class="max-w-4xl mx-auto px-6 py-8">

            <div class="mb-8">
                <h1 class="text-4xl font-black text-slate-900">
                    Edit Supervisor
                </h1>

                <p class="text-slate-500 mt-2">
                    Perbarui akun supervisor untuk cabang yang Anda kelola.
                </p>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <form action="{{ route('manager.supervisors.update', $supervisor->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-8 space-y-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    Nama Depan
                                </label>

                                <input type="text" name="first_name"
                                    value="{{ old('first_name', $supervisor->first_name) }}"
                                    placeholder="Contoh: Ahmad"
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">

                                @error('first_name')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    Nama Belakang
                                </label>

                                <input type="text" name="last_name"
                                    value="{{ old('last_name', $supervisor->last_name) }}"
                                    placeholder="Contoh: Ramadhan"
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">

                                @error('last_name')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Username
                            </label>

                            <input type="text" name="username"
                                value="{{ old('username', $supervisor->username) }}"
                                placeholder="Contoh: supervisor_cianjur"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">

                            @error('username')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Email
                            </label>

                            <input type="email" name="email"
                                value="{{ old('email', $supervisor->email) }}"
                                placeholder="Contoh: supervisor@gmail.com"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">

                            @error('email')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                No HP
                            </label>

                            <input type="text" name="no_hp"
                                value="{{ old('no_hp', $supervisor->no_hp) }}"
                                placeholder="Contoh: 081234567890"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">

                            @error('no_hp')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Password Baru
                            </label>

                            <input type="password" name="password"
                                placeholder="Kosongkan jika tidak ingin mengganti password"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">

                            @error('password')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Status
                            </label>

                            <select name="status"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="active"
                                    {{ old('status', $supervisor->status) == 'active' ? 'selected' : '' }}>
                                    Aktif
                                </option>

                                <option value="inactive"
                                    {{ old('status', $supervisor->status) == 'inactive' ? 'selected' : '' }}>
                                    Nonaktif
                                </option>
                            </select>

                            @error('status')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="border-t border-slate-200 px-8 py-5 flex justify-end gap-3">
                        <a href="{{ route('manager.supervisors.index') }}"
                            class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition">
                            Batal
                        </a>

                        <button type="submit"
                            class="px-6 py-3 rounded-2xl bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-700/20 hover:bg-emerald-800 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
