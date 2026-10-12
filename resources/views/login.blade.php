<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - PyBeginner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased h-screen flex flex-col overflow-hidden">

    {{-- ===================== NAVBAR AUTH ===================== --}}
    <header class="border-b border-gray-100 flex-shrink-0 bg-white">
        <nav class="max-w-7xl mx-auto px-6 lg:px-10 h-20 flex items-center justify-between">
            {{-- Logo + Kembali ke Beranda --}}
            <div class="flex items-center gap-4 sm:gap-5">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-9 w-auto">
                </a>
                <span class="w-px h-6 bg-gray-200"></span>
                <a href="{{ route('beranda') }}" class="flex items-center gap-2 text-sm font-normal text-gray-600 hover:text-gray-900 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali ke Beranda
                </a>
            </div>

            {{-- Menu Kanan: Masuk & Daftar --}}
            <div class="flex items-center gap-6 sm:gap-8">
                <a href="{{ route('login') }}" class="text-sm font-bold text-emerald-700">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="text-sm font-medium text-gray-700 hover:text-emerald-700 transition-colors">
                    Daftar
                </a>
                <span class="hidden sm:block w-px h-5 bg-gray-200"></span>
                <a href="{{ route('profile') }}" class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center text-white hover:bg-emerald-700 transition-colors">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </a>
            </div>
        </nav>
    </header>

    {{-- ===================== KONTEN LOGIN ===================== --}}
    <section class="relative flex-1 flex items-center justify-center overflow-hidden bg-gradient-to-br from-emerald-50/80 via-slate-50/40 to-blue-50/50 px-4 py-3">

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
             class="hidden lg:block absolute bottom-0 left-6 xl:left-14 z-0 w-[22rem] xl:w-[26rem] max-h-[72vh] object-contain pointer-events-none select-none">

        {{-- Kartu Login di tengah --}}
        <div class="relative z-10 w-full max-w-[380px] bg-white rounded-3xl p-6 sm:p-7 shadow-xl shadow-emerald-950/5 border border-gray-100/90">

            {{-- Logo brand --}}
            <div class="flex items-center gap-2 mb-1.5">
                <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-7 w-auto">
            </div>

            <h1 class="text-xl font-bold text-gray-900 mb-5">Selamat Datang Kembali</h1>

            {{-- Flash message --}}
            @if (session('status'))
                <div class="mb-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs px-3 py-2 rounded-xl flex items-start gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Google Error --}}
            @error('google_error')
                <div class="mb-3 bg-red-50 border border-red-200 text-red-700 text-xs px-3 py-2 rounded-xl flex items-start gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-red-500 shrink-0 mt-0.5"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <form method="POST" action="{{ route('login.store') }}" class="space-y-3.5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Alamat Email</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="email" name="email" id="email" required
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               class="w-full pl-9 pr-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-shadow">
                    </div>
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <i data-lucide="lock" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="password" name="password" id="password" required
                               placeholder="••••••••"
                               class="w-full pl-9 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-shadow">
                        <button type="button" onclick="togglePassword('password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ingat saya + Lupa password --}}
                <div class="flex items-center justify-between text-xs pt-0.5">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-gray-600">Ingat saya</span>
                    </label>
                    <a href="#" class="font-medium text-emerald-600 hover:text-emerald-700 transition-colors">Lupa kata sandi?</a>
                </div>

                {{-- Tombol Masuk --}}
                <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors flex items-center justify-center gap-2 shadow-md shadow-emerald-600/20">
                    Masuk ke PyBeginner
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-2.5 my-3.5">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-[11px] text-gray-400 uppercase tracking-wider">Atau masuk dengan</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Google Login --}}
            <a href="{{ route('auth.google') }}"
               class="w-full flex items-center justify-center gap-2.5 bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 text-gray-700 font-medium text-xs py-2.5 rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.52 12.27c0-.85-.07-1.47-.23-2.12H12v3.85h6.59c-.13 1.1-.86 2.76-2.47 3.87l-.02.15 3.59 2.78.25.02c2.28-2.1 3.58-5.2 3.58-8.55z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.07 7.94-2.91l-3.78-2.93c-1.02.7-2.4 1.19-4.16 1.19-3.18 0-5.88-2.1-6.84-4.99l-.14.01-3.73 2.89-.05.13C3.23 21.3 7.26 24 12 24z"/><path fill="#FBBC05" d="M5.16 14.36A7.17 7.17 0 014.76 12c0-.82.14-1.62.39-2.36L5.14 9.5 1.36 6.56l-.12.06A11.98 11.98 0 000 12c0 1.93.46 3.75 1.28 5.38l3.88-3.02z"/><path fill="#EA4335" d="M12 4.75c2.25 0 3.77.97 4.64 1.79l3.39-3.31C17.95 1.19 15.24 0 12 0 7.26 0 3.23 2.7 1.24 6.62l3.91 3.02c.97-2.89 3.67-4.89 6.85-4.89z"/></svg>
                Masuk dengan Google
            </a>

            {{-- Link ke Register --}}
            <p class="text-center text-xs text-gray-600 mt-3.5">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-emerald-600 font-semibold hover:underline">Daftar sekarang gratis</a>
            </p>
        </div>
    </section>

    <script>
        lucide.createIcons();

        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>