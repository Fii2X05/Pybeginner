<x-layouts.app title="Pengaturan Profil & Akun" active="profile">

    {{-- ===================== HEADER ===================== --}}
    <section class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Pengaturan Profil & Akun</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola informasi data diri, email, dan keamanan akun Anda di PyBeginner.</p>
    </section>

    {{-- ===================== FLASH MESSAGES ===================== --}}
    @if (session('status'))
        <div id="flash-status" class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-sm font-medium text-emerald-800 shadow-sm">
            <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('status') }}
        </div>
    @endif
    {{-- ===================== PROFIL CARD ===================== --}}
    <div class="mb-8 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        {{-- Gradient banner --}}
        <div class="h-24" style="background: linear-gradient(110deg, #ffffff 0%, #fffef5 12%, #fde68a 58%, #a7f3d0 100%);"></div>

        <div class="relative px-6 pb-6 sm:px-8">
            {{-- Avatar overlay --}}
            <div class="-mt-12 mb-4 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div class="flex items-end gap-5">
                    {{-- Avatar with upload --}}
                    <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" id="avatarForm" class="relative group shrink-0">
                        @csrf
                        @if ($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                 class="h-24 w-24 rounded-full border-4 border-white object-cover shadow-lg"
                                 onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                            <div class="hidden h-24 w-24 items-center justify-center rounded-full border-4 border-white bg-emerald-100 text-3xl font-bold text-emerald-800 shadow-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-full border-4 border-white bg-emerald-100 text-3xl font-bold text-emerald-800 shadow-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif

                        {{-- Overlay kamera --}}
                        <label for="avatarInput" title="Ganti Foto Profil"
                               class="absolute inset-0 flex cursor-pointer items-center justify-center rounded-full bg-black/40 opacity-0 transition-opacity group-hover:opacity-100">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <circle cx="12" cy="13" r="3"/>
                            </svg>
                        </label>
                        <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/webp"
                               class="hidden" onchange="document.getElementById('avatarForm').submit()">
                    </form>

                    {{-- Name & info --}}
                    <div class="pb-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                                {{ $user->role === 'admin' ? 'Admin' : 'Siswa / Member' }}
                            </span>
                        </div>
                        <p class="mt-0.5 text-sm text-gray-500">{{ $user->email }}</p>
                        @if ($user->bio)
                            <p class="mt-1.5 text-sm text-gray-600">{{ $user->bio }}</p>
                        @endif
                    </div>
                </div>

                {{-- Meta info --}}
                <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Bergabung sejak {{ $user->created_at->translatedFormat('F Y') }}
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Status: Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== INFORMASI PRIBADI ===================== --}}
    <div class="mb-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
        <div class="mb-6 flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100">
                <svg class="h-4.5 w-4.5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Informasi Pribadi</h3>
                <p class="text-xs text-gray-500">Perbarui informasi identitas dan kontak Anda.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
            @csrf
            @method('PUT')

            <div class="grid gap-5 sm:grid-cols-2">
                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat Email --}}
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:outline-none">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor WhatsApp / Telepon --}}
                <div class="sm:col-span-2">
                    <label for="phone" class="mb-1.5 block text-sm font-semibold text-gray-700">Nomor WhatsApp / Telepon <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <div class="flex items-stretch overflow-hidden rounded-xl border border-gray-200 bg-gray-50 transition focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20">
                        <span class="flex items-center gap-1.5 border-r border-gray-200 bg-gray-100 px-3 text-sm text-gray-500">
                            <span>🇮🇩</span>
                            <span>+62</span>
                        </span>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                               placeholder="8123456789"
                               class="w-full bg-transparent px-4 py-3 text-sm text-gray-900 focus:outline-none">
                    </div>
                    @error('phone')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bio / Tagline --}}
                <div class="sm:col-span-2">
                    <label for="bio" class="mb-1.5 block text-sm font-semibold text-gray-700">Bio / Tagline</label>
                    <textarea id="bio" name="bio" rows="3" maxlength="500" placeholder="Belajar Python dari nol dengan semangat dan konsisten setiap hari 🚀🐍"
                              class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:outline-none resize-none">{{ old('bio', $user->bio) }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Ceritakan dirimu sehingga teman belajar di sini bisa lebih kenal kamu.</p>
                    @error('bio')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('profileForm').reset()"
                        class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                        class="rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 active:scale-[.97]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- ===================== SESI AKUN PENGGUNA ===================== --}}
    <div class="mb-4 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-red-600">Sesi Akun Pengguna</h3>
                <p class="mt-0.5 text-xs text-gray-500">Keluar dari perangkat Anda atau hentikan menggunakan kerangka bersama.</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-100 active:scale-[.97]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar Akun
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-hide flash messages after 5s
        document.querySelectorAll('#flash-status').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity 0.5s, transform 0.5s';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-8px)';
                setTimeout(() => el.remove(), 500);
            }, 5000);
        });

        // Avatar file-size validation (max 2MB)
        document.getElementById('avatarInput')?.addEventListener('change', function () {
            if (this.files[0] && this.files[0].size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2 MB. Silakan pilih gambar yang lebih kecil.');
                this.value = '';
            }
        });
    </script>
    @endpush
</x-layouts.app>
