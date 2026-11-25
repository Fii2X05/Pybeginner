<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - PyBeginner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased min-h-screen flex flex-col">

    {{-- ===================== NAVBAR AUTH ===================== --}}
    <header class="border-b border-gray-100 flex-shrink-0 bg-white">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 h-14 sm:h-16 flex items-center justify-between">
            {{-- Logo + Kembali ke Beranda --}}
            <div class="flex items-center gap-3 sm:gap-5">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-7 sm:h-8 w-auto">
                </a>
                <span class="w-px h-5 sm:h-6 bg-gray-200"></span>
                <a href="{{ route('beranda') }}" class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm font-normal text-gray-600 hover:text-gray-900 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span class="hidden xs:inline">Kembali ke Beranda</span>
                    <span class="xs:hidden">Beranda</span>
                </a>
            </div>

            {{-- Menu Kanan: Masuk & Daftar --}}
            <div class="flex items-center gap-4 sm:gap-8">
                <a href="{{ route('login') }}" class="text-xs sm:text-sm font-medium text-gray-700 hover:text-emerald-700 transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="text-xs sm:text-sm font-bold text-emerald-700">
                    Daftar
                </a>
            </div>
        </nav>
    </header>

    {{-- ===================== KONTEN REGISTER ===================== --}}
    <section class="relative flex-1 flex items-center justify-center bg-gradient-to-br from-emerald-50/80 via-slate-50/40 to-blue-50/50 px-3 py-2 sm:py-3 overflow-y-auto lg:overflow-hidden">

        {{-- Dekorasi latar --}}
        <div class="hidden lg:block absolute top-10 left-10 w-72 h-72 bg-emerald-200/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="hidden lg:block absolute bottom-16 left-40 w-64 h-64 bg-blue-200/25 rounded-full blur-3xl pointer-events-none"></div>

        {{-- Bintang dekoratif --}}
        <div class="hidden lg:block absolute top-12 left-[28%] pointer-events-none">
            <svg class="w-7 h-7 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7L12 16.4 5.7 21l2.3-7L2 9.4h7.6z"/></svg>
        </div>
        <div class="hidden lg:block absolute top-24 left-[14%] pointer-events-none">
            <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7L12 16.4 5.7 21l2.3-7L2 9.4h7.6z"/></svg>
        </div>

        {{-- Maskot di kiri bawah --}}
        <img src="{{ asset('images/mascot-python.png') }}"
             alt="Maskot PyBeginner"
             class="hidden 2xl:block absolute bottom-0 left-6 xl:left-14 z-0 w-[20rem] xl:w-[23rem] max-h-[65vh] object-contain pointer-events-none select-none">

        {{-- Kartu Register di tengah --}}
        <div class="relative z-10 w-full max-w-[390px] bg-white rounded-2xl p-4 sm:p-5 shadow-xl shadow-emerald-950/5 border border-gray-100/90 my-auto">
            {{-- Logo brand --}}
            <div class="flex items-center gap-2 mb-1">
                <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-6 w-auto">
            </div>

            <h1 class="text-base sm:text-lg font-bold text-gray-900 mb-0.5">Buat Akun Baru</h1>
            <p class="text-[11px] text-gray-500 mb-3 leading-tight">
                Mulai belajar pemrograman Python bersama PyBeginner.
            </p>

            {{-- Flash message --}}
            @if (session('status'))
                <div class="mb-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] px-2.5 py-1.5 rounded-lg flex items-start gap-1.5">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-500 shrink-0 mt-0.5"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Form Register --}}
            <form id="register-form" method="POST" action="{{ route('register.store') }}" class="space-y-2">
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <div class="flex items-center justify-between mb-0.5">
                        <label for="name" class="block text-[11px] font-semibold text-gray-700">Nama Lengkap</label>
                        <span class="text-[10px] text-gray-400">Wajib diisi</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <i data-lucide="user" class="w-3.5 h-3.5"></i>
                        </span>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autocomplete="name"
                            placeholder="Contoh: Sukma Ananda"
                            class="w-full pl-8 pr-3 py-1.5 text-xs bg-[#f8faff] border @error('name') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white focus:border-transparent transition"
                        >
                    </div>
                    @error('name')
                        <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat Email --}}
                <div>
                    <div class="flex items-center justify-between mb-0.5">
                        <label for="email" class="block text-[11px] font-semibold text-gray-700">Alamat Email</label>
                        <span class="text-[10px] text-gray-400">Untuk verifikasi akun</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                        </span>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            placeholder="sukma@example.com"
                            class="w-full pl-8 pr-3 py-1.5 text-xs bg-[#f8faff] border @error('email') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white focus:border-transparent transition"
                        >
                    </div>
                    @error('email')
                        <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kata Sandi --}}
                <div>
                    <div class="flex items-center justify-between mb-0.5">
                        <label for="password" class="block text-[11px] font-semibold text-gray-700">Kata Sandi</label>
                        <span class="text-[10px] text-gray-400">Minimal 8 karakter</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                        </span>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="w-full pl-8 pr-8 py-1.5 text-xs bg-[#f8faff] border @error('password') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white focus:border-transparent transition"
                        >
                        <button type="button" id="toggle-password" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>

                    {{-- Password Strength Bars --}}
                    <div class="grid grid-cols-4 gap-1 mt-1">
                        <div id="bar-1" class="h-1 rounded-full bg-slate-200 transition-colors"></div>
                        <div id="bar-2" class="h-1 rounded-full bg-slate-200 transition-colors"></div>
                        <div id="bar-3" class="h-1 rounded-full bg-slate-200 transition-colors"></div>
                        <div id="bar-4" class="h-1 rounded-full bg-slate-200 transition-colors"></div>
                    </div>
                    <p id="password-hint" class="text-[10px] text-gray-400 mt-0.5 leading-tight">
                        Gunakan minimal 8 karakter dengan huruf kapital, angka, dan simbol.
                    </p>

                    @error('password')
                        <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Kata Sandi --}}
                <div>
                    <div class="flex items-center justify-between mb-0.5">
                        <label for="password_confirmation" class="block text-[11px] font-semibold text-gray-700">Konfirmasi Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                <path d="M3 3v5h5"/>
                                <rect width="6" height="5" x="9" y="12" rx="1"/>
                                <path d="M10 12v-1.5a2 2 0 1 1 4 0v1.5"/>
                            </svg>
                        </span>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ulangi kata sandi kamu"
                            class="w-full pl-8 pr-8 py-1.5 text-xs bg-[#f8faff] border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white focus:border-transparent transition"
                        >
                        <button type="button" id="toggle-password-confirmation" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <button
                    type="submit"
                    id="btn-register-submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white font-semibold py-2 rounded-lg text-xs sm:text-sm transition-all shadow-sm shadow-emerald-600/20 flex items-center justify-center gap-1.5 mt-2"
                >
                    <span>Buat Akun Sekarang</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-2 my-2">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-[10px] text-gray-400 uppercase tracking-wider">Atau</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Tombol Sudah punya akun --}}
            <a href="{{ route('login') }}"
               class="w-full bg-[#275988] hover:bg-[#1f486e] text-white font-medium py-1.5 sm:py-2 rounded-lg text-xs transition-colors flex items-center justify-center gap-1 shadow-xs">
                Sudah memiliki akun? <span class="font-bold">Masuk di sini</span>
            </a>
        </div>
    </section>

    <script>
        lucide.createIcons();

        // Toggle Password Visibility
        function setupPasswordToggle(buttonId, inputId) {
            const btn = document.getElementById(buttonId);
            const input = document.getElementById(inputId);
            if (!btn || !input) return;

            btn.addEventListener('click', function () {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                btn.innerHTML = isPassword
                    ? '<i data-lucide="eye-off" class="w-4 h-4"></i>'
                    : '<i data-lucide="eye" class="w-4 h-4"></i>';
                lucide.createIcons();
            });
        }

        setupPasswordToggle('toggle-password', 'password');
        setupPasswordToggle('toggle-password-confirmation', 'password_confirmation');

        // Dynamic Password Strength Meter
        const passwordInput = document.getElementById('password');
        const passwordHint = document.getElementById('password-hint');
        const bars = [
            document.getElementById('bar-1'),
            document.getElementById('bar-2'),
            document.getElementById('bar-3'),
            document.getElementById('bar-4'),
        ];

        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                const val = passwordInput.value;
                let score = 0;
                const hasLen = val.length >= 8;
                const hasUpper = /[A-Z]/.test(val);
                const hasLower = /[a-z]/.test(val);
                const hasNum = /[0-9]/.test(val);
                const hasSymbol = /[^A-Za-z0-9]/.test(val);

                if (hasLen) score++;
                if (hasNum && (hasUpper || hasLower)) score++;
                if (hasUpper || hasSymbol) score++;
                if ((hasUpper && hasSymbol) || (hasLen && hasUpper && hasNum && hasLower) || (val.length >= 10 && hasNum && hasSymbol)) score++;

                bars.forEach((bar, index) => {
                    bar.className = 'h-1 rounded-full transition-colors';
                    if (val.length === 0) {
                        bar.classList.add('bg-slate-200');
                    } else if (index < score) {
                        if (score === 1) {
                            bar.classList.add('bg-red-400');
                        } else if (score === 2) {
                            bar.classList.add('bg-amber-400');
                        } else if (score === 3) {
                            bar.classList.add('bg-blue-400');
                        } else {
                            bar.classList.add('bg-emerald-500');
                        }
                    } else {
                        bar.classList.add('bg-slate-200');
                    }
                });

                if (passwordHint) {
                    if (val.length === 0) {
                        passwordHint.textContent = 'Gunakan minimal 8 karakter dengan huruf kapital, angka, dan simbol.';
                        passwordHint.className = 'text-[10px] text-gray-400 mt-0.5 leading-tight';
                    } else if (score === 4) {
                        passwordHint.textContent = 'Kata sandi sangat kuat!';
                        passwordHint.className = 'text-[10px] text-emerald-600 font-medium mt-0.5 leading-tight';
                    } else if (!hasUpper) {
                        passwordHint.textContent = 'Tambahkan huruf kapital (A-Z) agar bar penuh.';
                        passwordHint.className = 'text-[10px] text-blue-500 mt-0.5 leading-tight';
                    } else if (!hasSymbol) {
                        passwordHint.textContent = 'Tambahkan simbol (@, #, $) agar bar penuh.';
                        passwordHint.className = 'text-[10px] text-blue-500 mt-0.5 leading-tight';
                    } else if (!hasLen) {
                        passwordHint.textContent = 'Minimal 8 karakter agar aman.';
                        passwordHint.className = 'text-[10px] text-amber-500 mt-0.5 leading-tight';
                    } else {
                        passwordHint.textContent = 'Bagus! Kombinasikan huruf besar, angka, dan simbol.';
                        passwordHint.className = 'text-[10px] text-blue-500 mt-0.5 leading-tight';
                    }
                }
            });
        }
    </script>
</body>
</html>