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
<body class="bg-white text-gray-900 antialiased">

    {{-- ===================== NAVBAR SEDERHANA ===================== --}}
    <header class="border-b border-gray-100">
        <nav class="max-w-7xl mx-auto px-6 lg:px-10 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="bot" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <span class="text-lg font-bold text-gray-900">PyBeginner</span>
            </a>

            <a href="{{ route('beranda') }}" class="hidden sm:flex items-center gap-1 text-sm font-medium text-gray-600 hover:text-emerald-600">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali ke Beranda
            </a>

            <div class="flex items-center gap-5">
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-emerald-600">Masuk</a>
                <a href="{{ route('register') }}" class="text-sm font-semibold text-emerald-700 border-b-2 border-emerald-600 pb-1">Daftar</a>
                <span class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center text-white">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </span>
            </div>
        </nav>
    </header>

    {{-- ===================== KONTEN REGISTER ===================== --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-white via-emerald-50/70 to-emerald-50 min-h-[calc(100vh-81px)]">
        {{-- Blur dekoratif --}}
        <div class="hidden lg:block absolute -top-10 left-0 w-96 h-96 bg-blue-100/60 rounded-full blur-3xl z-0"></div>
        <div class="hidden lg:block absolute bottom-0 left-10 w-80 h-80 bg-emerald-100/60 rounded-full blur-3xl z-0"></div>

        {{-- Maskot --}}
        <img src="{{ asset('images/mascot-python.png') }}"
             alt="Maskot ular Python PyBeginner"
             class="hidden lg:block absolute bottom-0 left-0 w-64 xl:w-72 opacity-95 pointer-events-none select-none z-0">

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 py-6 flex justify-center items-center">
            {{-- Kartu Register --}}
            <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="bot" class="w-3.5 h-3.5 text-emerald-600"></i>
                    </div>
                    <span class="font-bold text-sm text-gray-900">PyBeginner</span>
                </div>

                <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <i data-lucide="user" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" name="name" id="name" required
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Sukma Ananda"
                                   class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                        <div class="relative">
                            <i data-lucide="mail" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="email" name="email" id="email" required
                                   value="{{ old('email') }}"
                                   placeholder="sukma@example.com"
                                   class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        </div>
                        @error('email')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi</label>
                        <div class="relative">
                            <i data-lucide="lock" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="password" name="password" id="password" required minlength="8"
                                   placeholder="Minimal 8 karakter"
                                   class="w-full pl-10 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <button type="button" onclick="togglePassword('password', this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <i data-lucide="lock-keyhole" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   placeholder="Ulangi kata sandi kamu"
                                   class="w-full pl-10 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <button type="button" onclick="togglePassword('password_confirmation', this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                        Buat Akun Sekarang
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>

                <p class="text-center text-sm text-gray-600 mt-5">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-emerald-700 font-semibold hover:underline">Masuk di sini</a>
                </p>

                <div class="flex items-center gap-3 my-5">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs text-gray-400">ATAU</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <button type="button"
                        class="w-full flex items-center justify-center gap-2 bg-gray-50 border border-gray-200 hover:bg-gray-100 text-gray-700 font-medium text-sm py-2.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.52 12.27c0-.85-.07-1.47-.23-2.12H12v3.85h6.59c-.13 1.1-.86 2.76-2.47 3.87l-.02.15 3.59 2.78.25.02c2.28-2.1 3.58-5.2 3.58-8.55z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.07 7.94-2.91l-3.78-2.93c-1.02.7-2.4 1.19-4.16 1.19-3.18 0-5.88-2.1-6.84-4.99l-.14.01-3.73 2.89-.05.13C3.23 21.3 7.26 24 12 24z"/><path fill="#FBBC05" d="M5.16 14.36A7.17 7.17 0 014.76 12c0-.82.14-1.62.39-2.36L5.14 9.5 1.36 6.56l-.12.06A11.98 11.98 0 000 12c0 1.93.46 3.75 1.28 5.38l3.88-3.02z"/><path fill="#EA4335" d="M12 4.75c2.25 0 3.77.97 4.64 1.79l3.39-3.31C17.95 1.19 15.24 0 12 0 7.26 0 3.23 2.7 1.24 6.62l3.91 3.02c.97-2.89 3.67-4.89 6.85-4.89z"/></svg>
                    Daftar dengan Google
                </button>
            </div>
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