<x-layouts.admin title="Pengaturan - Admin" active="pengaturan">
    <div class="mb-8">
        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
            <span>SISTEM &amp; AKUN</span>
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
        </div>
        <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
            Pengaturan Akun &amp; Sistem
        </h1>
        <p class="text-sm text-slate-500 mt-1">Konfigurasi akun administrator dan informasi lingkungan sistem PyBeginner.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Edit Profile Form -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-100 shadow-xs">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Informasi Profil Administrator</h2>
                    <p class="text-xs text-slate-400">Perbarui nama dan email login administrator</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.pengaturan.profile') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $admin->name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm shadow-emerald-600/20 transition">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-100 shadow-xs">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <i data-lucide="lock" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Ubah Kata Sandi</h2>
                    <p class="text-xs text-slate-400">Pastikan akun menggunakan kata sandi yang aman</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.pengaturan.password') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Kata Sandi Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold transition">
                        Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>

        <!-- System Information -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 sm:p-8 border border-slate-100 shadow-xs">
            <h2 class="text-base font-bold text-slate-900 mb-4">Informasi Lingkungan Sistem</h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-xs text-slate-400 font-medium">Framework</div>
                    <div class="text-base font-bold text-slate-800 mt-1">Laravel {{ app()->version() }}</div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-xs text-slate-400 font-medium">PHP Version</div>
                    <div class="text-base font-bold text-slate-800 mt-1">PHP {{ PHP_VERSION }}</div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-xs text-slate-400 font-medium">Database Driver</div>
                    <div class="text-base font-bold text-slate-800 mt-1 uppercase">{{ config('database.default') }}</div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-xs text-slate-400 font-medium">Lingkungan (Env)</div>
                    <div class="text-base font-bold text-emerald-600 mt-1 uppercase">{{ config('app.env') }}</div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
