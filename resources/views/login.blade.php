<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - PyBeginner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        @keyframes captchaShake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }
        .captcha-shake {
            animation: captchaShake 0.4s ease-in-out;
        }
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
        <div class="relative z-10 w-full max-w-[400px] bg-white rounded-3xl p-6 sm:p-7 shadow-xl shadow-emerald-950/5 border border-gray-100/90">

            {{-- Logo brand --}}
            <div class="flex items-center gap-2 mb-1.5">
                <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-7 w-auto">
            </div>

            <h1 class="text-xl font-bold text-gray-900 mb-1">Selamat Datang Kembali</h1>
            <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                Masuk ke akun PyBeginner kamu untuk melanjutkan pembelajaran.
            </p>

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

            {{-- Google Login --}}
            <a id="btn-google-login" href="{{ route('auth.google') }}"
               class="w-full flex items-center justify-center gap-2.5 bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 text-gray-700 font-medium text-xs sm:text-sm py-2.5 sm:py-3 rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.52 12.27c0-.85-.07-1.47-.23-2.12H12v3.85h6.59c-.13 1.1-.86 2.76-2.47 3.87l-.02.15 3.59 2.78.25.02c2.28-2.1 3.58-5.2 3.58-8.55z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.07 7.94-2.91l-3.78-2.93c-1.02.7-2.4 1.19-4.16 1.19-3.18 0-5.88-2.1-6.84-4.99l-.14.01-3.73 2.89-.05.13C3.23 21.3 7.26 24 12 24z"/><path fill="#FBBC05" d="M5.16 14.36A7.17 7.17 0 014.76 12c0-.82.14-1.62.39-2.36L5.14 9.5 1.36 6.56l-.12.06A11.98 11.98 0 000 12c0 1.93.46 3.75 1.28 5.38l3.88-3.02z"/><path fill="#EA4335" d="M12 4.75c2.25 0 3.77.97 4.64 1.79l3.39-3.31C17.95 1.19 15.24 0 12 0 7.26 0 3.23 2.7 1.24 6.62l3.91 3.02c.97-2.89 3.67-4.89 6.85-4.89z"/></svg>
                Masuk dengan Google
            </a>

            {{-- Divider --}}
            <div class="flex items-center gap-2.5 my-3.5">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-[11px] text-gray-400 uppercase tracking-wider">Atau masuk dengan email</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Form Login --}}
            <form id="login-form" method="POST" action="{{ route('login.store') }}" class="space-y-3">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="w-full pl-9 pr-3 py-2.5 text-sm border @error('email') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition"
                        >
                    </div>
                    @error('email')
                        <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Kata sandi kamu"
                            class="w-full pl-9 pr-10 py-2.5 text-sm border @error('password') border-red-400 bg-red-50 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition"
                        >
                        <button type="button" id="toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-start gap-2">
                    <input id="remember" type="checkbox" name="remember" @checked(old('remember')) class="mt-0.5 w-3.5 h-3.5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-400">
                    <div>
                        <label for="remember" class="text-xs text-gray-600">Ingat saya di perangkat ini</label>
                        <p class="text-[11px] text-gray-500">Tetap masuk sampai kamu keluar dari akun.</p>
                    </div>
                </div>

                {{-- Captcha Error from Server --}}
                @error('captcha')
                    <div class="bg-red-50 border border-red-200 text-red-700 text-xs px-3 py-2 rounded-xl flex items-start gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 text-red-500 shrink-0 mt-0.5"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                {{-- Captcha Hidden Input --}}
                <input type="hidden" name="captcha_token" id="captcha-token" value="">

                {{-- Submit --}}
                <button
                    type="submit"
                    id="btn-login-submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 sm:py-3 rounded-xl text-sm transition-colors shadow-sm shadow-emerald-600/20 flex items-center justify-center gap-2"
                >
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    Masuk Sekarang
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-2.5 my-3.5">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-[11px] text-gray-400 uppercase tracking-wider">Atau</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Tombol Belum punya akun --}}
            <a href="{{ route('register') }}"
               class="w-full bg-[#275988] hover:bg-[#1f486e] text-white font-medium py-2.5 sm:py-3 rounded-xl text-xs sm:text-sm transition-colors flex items-center justify-center gap-1.5 shadow-md shadow-[#275988]/20">
                Belum memiliki akun? <span class="font-bold">Daftar di sini</span>
            </a>
        </div>
    </section>

    {{-- ===================== MODAL CAPTCHA PUZZLE SLIDER ===================== --}}
    <div id="puzzle-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200">
        <div id="puzzle-modal-card" class="relative w-full max-w-[360px] bg-white rounded-2xl p-4 sm:p-5 shadow-2xl border border-gray-100">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Verifikasi Keamanan</h3>
                        <p class="text-[11px] text-gray-500">Geser puzzle ke posisi yang tepat</p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" id="btn-refresh-puzzle" title="Ganti Gambar" class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="rotate-cw" class="w-4 h-4"></i>
                    </button>
                    <button type="button" id="btn-close-puzzle" title="Tutup" class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            {{-- Puzzle Canvas Stage (320x160) --}}
            <div id="puzzle-stage-container" class="relative w-[320px] h-[160px] mx-auto rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-inner select-none">
                {{-- Background Canvas --}}
                <canvas id="puzzle-bg-canvas" width="320" height="160" class="block w-full h-full"></canvas>
                {{-- Floating Puzzle Piece Canvas --}}
                <canvas id="puzzle-piece-canvas" width="320" height="160" class="absolute top-0 left-0 pointer-events-none"></canvas>
                
                {{-- Loading Overlay --}}
                <div id="puzzle-loading" class="absolute inset-0 bg-white/90 flex items-center justify-center gap-2 text-xs text-gray-600 font-medium">
                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin text-emerald-600"></i>
                    <span>Memuat verifikasi PyBeginner...</span>
                </div>

                {{-- Status Alert Overlay --}}
                <div id="puzzle-feedback" class="absolute bottom-0 inset-x-0 py-1.5 px-3 text-[11px] font-semibold text-center transition-all duration-200 translate-y-full flex items-center justify-center gap-1.5 text-white">
                    <span id="puzzle-feedback-text"></span>
                </div>
            </div>

            {{-- Slider Control --}}
            <div class="mt-4">
                <div id="slider-track" class="relative w-[320px] mx-auto h-11 bg-slate-100 rounded-xl border border-slate-200 select-none overflow-hidden flex items-center cursor-pointer">
                    {{-- Progress Fill --}}
                    <div id="slider-progress" class="absolute left-0 top-0 bottom-0 bg-emerald-500/25 w-0 transition-[width] pointer-events-none"></div>
                    
                    {{-- Slider Helper Text --}}
                    <span id="slider-hint" class="absolute inset-0 flex items-center justify-center text-xs text-gray-500 font-medium pointer-events-none select-none pl-6">
                        Geser untuk melengkapi puzzle
                    </span>

                    {{-- Slider Draggable Thumb Handle --}}
                    <div id="slider-thumb" class="absolute left-1 top-1 bottom-1 w-10 bg-white hover:bg-emerald-50 border border-slate-300 rounded-lg shadow-sm flex items-center justify-center text-emerald-600 cursor-grab active:cursor-grabbing transition-colors z-10 select-none">
                        <i data-lucide="chevrons-right" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            {{-- Security Footer Note --}}
            <div class="mt-3 flex items-center justify-between text-[10px] text-gray-400 px-1">
                <span class="flex items-center gap-1">
                    <i data-lucide="zap" class="w-3 h-3 text-emerald-500"></i> Ringan & Responsif (0ms lag)
                </span>
                <span>PyBeginner Guard</span>
            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();

        // Toggle show/hide password
        const toggleBtn = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                toggleBtn.innerHTML = isPassword
                    ? '<i data-lucide="eye-off" class="w-4 h-4"></i>'
                    : '<i data-lucide="eye" class="w-4 h-4"></i>';
                lucide.createIcons();
            });
        }

        // ===================== PUZZLE SLIDER CAPTCHA LOGIC =====================
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const puzzleModal = document.getElementById('puzzle-modal');
        const puzzleModalCard = document.getElementById('puzzle-modal-card');
        const btnClosePuzzle = document.getElementById('btn-close-puzzle');
        const btnRefreshPuzzle = document.getElementById('btn-refresh-puzzle');
        const btnGoogleLogin = document.getElementById('btn-google-login');
        const btnLoginSubmit = document.getElementById('btn-login-submit');
        const loginForm = document.getElementById('login-form');
        const captchaTokenInput = document.getElementById('captcha-token');

        const bgCanvas = document.getElementById('puzzle-bg-canvas');
        const pieceCanvas = document.getElementById('puzzle-piece-canvas');
        const bgCtx = bgCanvas.getContext('2d');
        const pieceCtx = pieceCanvas.getContext('2d');
        const puzzleLoading = document.getElementById('puzzle-loading');
        const puzzleFeedback = document.getElementById('puzzle-feedback');
        const puzzleFeedbackText = document.getElementById('puzzle-feedback-text');
        const puzzleStageContainer = document.getElementById('puzzle-stage-container');

        const sliderTrack = document.getElementById('slider-track');
        const sliderThumb = document.getElementById('slider-thumb');
        const sliderProgress = document.getElementById('slider-progress');
        const sliderHint = document.getElementById('slider-hint');

        let challenge = null;
        let isVerified = false;
        let isDragging = false;
        let startX = 0;
        let startThumbX = 0;
        let currentThumbX = 0;
        let startTime = 0;
        let trail = [];
        let isVerifying = false;
        let pendingAction = null; // 'google' atau 'email'

        // Path generator keping jigsaw dengan lekukan dan tonjolan
        function drawJigsawPath(ctx, x, y, width = 44, radius = 8) {
            const half = width / 2;
            ctx.beginPath();
            ctx.moveTo(x, y);

            // Sisi Atas (Tonjolan cembung)
            ctx.lineTo(x + half - radius, y);
            ctx.arc(x + half, y - radius + 2, radius, Math.PI * 0.8, Math.PI * 0.2, false);
            ctx.lineTo(x + width, y);

            // Sisi Kanan (Tonjolan cembung)
            ctx.lineTo(x + width, y + half - radius);
            ctx.arc(x + width + radius - 2, y + half, radius, Math.PI * 1.3, Math.PI * 0.7, false);
            ctx.lineTo(x + width, y + width);

            // Sisi Bawah (Lekukan cekung)
            ctx.lineTo(x + half + radius, y + width);
            ctx.arc(x + half, y + width - radius + 2, radius, Math.PI * 0.2, Math.PI * 0.8, true);
            ctx.lineTo(x, y + width);

            // Sisi Kiri (Lekukan cekung)
            ctx.lineTo(x, y + half + radius);
            ctx.arc(x + radius - 2, y + half, radius, Math.PI * 0.7, Math.PI * 1.3, true);
            ctx.lineTo(x, y);

            ctx.closePath();
        }

        // Muat tantangan baru dari server (ringan: mengembalikan token, gambar PyBeginner hijau, dan posisi target)
        async function fetchChallenge() {
            puzzleLoading.classList.remove('hidden');
            hideFeedback();
            resetSlider();

            try {
                const response = await fetch('{{ route("captcha.puzzle.challenge") }}', {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) throw new Error('Gagal memuat tantangan');

                challenge = await response.json();
                renderPuzzle(challenge);
            } catch (err) {
                showFeedback('Gagal memuat puzzle. Coba lagi.', 'error');
                puzzleLoading.classList.add('hidden');
            }
        }

        // Render puzzle di canvas sisi klien (0 beban komputasi server)
        function renderPuzzle(data) {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.src = data.image_url;

            img.onload = () => {
                const { target_x, target_y, piece_width, piece_radius } = data;

                // 1. Gambar latar belakang PyBeginner lengkap
                bgCtx.clearRect(0, 0, 320, 160);
                bgCtx.drawImage(img, 0, 0, 320, 160);

                // 2. Buat slot berongga pada posisi target di background canvas
                bgCtx.save();
                drawJigsawPath(bgCtx, target_x, target_y, piece_width, piece_radius);
                bgCtx.fillStyle = 'rgba(0, 0, 0, 0.65)';
                bgCtx.fill();
                bgCtx.lineWidth = 2;
                bgCtx.strokeStyle = 'rgba(255, 255, 255, 0.9)';
                bgCtx.setLineDash([4, 3]);
                bgCtx.stroke();
                bgCtx.restore();

                // 3. Potong kepingan puzzle di piece canvas (awalnya berada di koordinat x=0)
                pieceCtx.clearRect(0, 0, 320, 160);
                pieceCtx.save();
                pieceCtx.shadowColor = 'rgba(0, 0, 0, 0.55)';
                pieceCtx.shadowBlur = 8;
                pieceCtx.shadowOffsetX = 2;
                pieceCtx.shadowOffsetY = 2;

                drawJigsawPath(pieceCtx, 0, target_y, piece_width, piece_radius);
                pieceCtx.clip();

                // Geser gambar sebesar -target_x agar potongan gambar sesuai dengan target
                pieceCtx.drawImage(img, -target_x, 0, 320, 160);

                // Garis tepi keping putih cerah
                pieceCtx.lineWidth = 2;
                pieceCtx.strokeStyle = '#ffffff';
                pieceCtx.stroke();
                pieceCtx.restore();

                pieceCanvas.style.transform = 'translateX(0px)';
                puzzleLoading.classList.add('hidden');
            };

            img.onerror = () => {
                showFeedback('Gambar tidak dapat dimuat.', 'error');
                puzzleLoading.classList.add('hidden');
            };
        }

        // Buka / Tutup Modal
        function openModal() {
            puzzleModal.classList.remove('hidden');
            fetchChallenge();
            lucide.createIcons();
        }

        function closeModal() {
            if (!isVerified) {
                pendingAction = null;
            }
            puzzleModal.classList.add('hidden');
        }

        btnClosePuzzle.addEventListener('click', closeModal);
        btnRefreshPuzzle.addEventListener('click', () => {
            btnRefreshPuzzle.classList.add('animate-spin');
            fetchChallenge().finally(() => {
                setTimeout(() => btnRefreshPuzzle.classList.remove('animate-spin'), 400);
            });
        });

        // 1. Trigger Captcha saat klik Masuk dengan Google
        if (btnGoogleLogin) {
            btnGoogleLogin.addEventListener('click', (e) => {
                if (!isVerified) {
                    e.preventDefault();
                    pendingAction = 'google';
                    openModal();
                }
            });
        }

        // 2. Trigger Captcha saat submit form Email / Kata Sandi
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => {
                if (!loginForm.checkValidity()) {
                    return; // Biarkan validasi bawaan browser (email wajib diisi, dll)
                }

                if (!isVerified || !captchaTokenInput.value) {
                    e.preventDefault();
                    pendingAction = 'email';
                    openModal();
                }
            });
        }

        // Reset posisi slider
        function resetSlider() {
            currentThumbX = 0;
            sliderThumb.style.transform = 'translateX(0px)';
            sliderProgress.style.width = '0px';
            pieceCanvas.style.transform = 'translateX(0px)';
            sliderHint.style.opacity = '1';
        }

        // Tampilkan feedback status di bawah canvas
        function showFeedback(text, type = 'success') {
            puzzleFeedbackText.textContent = text;
            puzzleFeedback.className = 'absolute bottom-0 inset-x-0 py-1.5 px-3 text-[11px] font-semibold text-center transition-all duration-200 translate-y-0 flex items-center justify-center gap-1.5 text-white ' +
                (type === 'success' ? 'bg-emerald-600' : 'bg-red-600');
        }

        function hideFeedback() {
            puzzleFeedback.classList.add('translate-y-full');
        }

        // Handler drag pergerakan slider
        function onDragStart(clientX) {
            if (isVerified || !challenge || isVerifying) return;
            isDragging = true;
            startX = clientX;
            startThumbX = currentThumbX;
            startTime = Date.now();
            trail = [{ x: 0, y: 0, t: 0 }];
            sliderHint.style.opacity = '0.3';
            hideFeedback();
        }

        function onDragMove(clientX, clientY) {
            if (!isDragging) return;

            const deltaX = clientX - startX;
            const maxThumbX = 320 - sliderThumb.clientWidth - 8;
            currentThumbX = Math.max(0, Math.min(startThumbX + deltaX, maxThumbX));

            const ratio = currentThumbX / maxThumbX;
            const maxPieceX = 320 - (challenge.piece_width || 44);
            const currentPieceX = ratio * maxPieceX;

            sliderThumb.style.transform = `translateX(${currentThumbX}px)`;
            sliderProgress.style.width = `${currentThumbX + 20}px`;
            // Performa 60fps dengan CSS transform akselerasi GPU
            pieceCanvas.style.transform = `translateX(${currentPieceX}px)`;

            trail.push({
                x: Math.round(currentPieceX),
                y: clientY || 0,
                t: Date.now() - startTime
            });
        }

        async function onDragEnd() {
            if (!isDragging) return;
            isDragging = false;

            const maxThumbX = 320 - sliderThumb.clientWidth - 8;
            const ratio = currentThumbX / maxThumbX;
            const maxPieceX = 320 - (challenge.piece_width || 44);
            const finalPieceX = ratio * maxPieceX;
            const duration = Date.now() - startTime;

            isVerifying = true;

            try {
                const response = await fetch('{{ route("captcha.puzzle.verify") }}', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        token: challenge.token,
                        position_x: finalPieceX,
                        duration: duration,
                        trail: trail
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Sukses verifikasi!
                    isVerified = true;
                    captchaTokenInput.value = result.verification_token;
                    showFeedback('✓ Verifikasi Berhasil! Memproses...', 'success');

                    setTimeout(() => {
                        closeModal();
                        if (pendingAction === 'google' && btnGoogleLogin) {
                            const googleLoginUrl = new URL(btnGoogleLogin.href);
                            googleLoginUrl.searchParams.set('remember', document.getElementById('remember').checked ? '1' : '0');
                            window.location.href = googleLoginUrl.toString();
                        } else if (pendingAction === 'email' && loginForm) {
                            if (btnLoginSubmit) {
                                btnLoginSubmit.disabled = true;
                                btnLoginSubmit.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Sedang masuk...';
                                lucide.createIcons();
                            }
                            loginForm.submit();
                        }
                    }, 400);
                } else {
                    // Gagal: goyang canvas dan muat ulang tantangan
                    showFeedback(result.message || 'Posisi belum pas, coba lagi.', 'error');
                    puzzleStageContainer.classList.add('captcha-shake');
                    setTimeout(() => {
                        puzzleStageContainer.classList.remove('captcha-shake');
                        resetSlider();
                        fetchChallenge();
                    }, 650);
                }
            } catch (err) {
                showFeedback('Terjadi kesalahan koneksi.', 'error');
                resetSlider();
            } finally {
                isVerifying = false;
            }
        }

        // Event listener Mouse
        sliderThumb.addEventListener('mousedown', (e) => {
            onDragStart(e.clientX);
            e.preventDefault();
        });

        window.addEventListener('mousemove', (e) => {
            if (isDragging) {
                onDragMove(e.clientX, e.clientY);
            }
        });

        window.addEventListener('mouseup', () => {
            if (isDragging) {
                onDragEnd();
            }
        });

        // Event listener Layar Sentuh (Mobile/Tablet)
        sliderThumb.addEventListener('touchstart', (e) => {
            if (e.touches.length > 0) {
                onDragStart(e.touches[0].clientX);
            }
        }, { passive: true });

        window.addEventListener('touchmove', (e) => {
            if (isDragging && e.touches.length > 0) {
                onDragMove(e.touches[0].clientX, e.touches[0].clientY);
                e.preventDefault();
            }
        }, { passive: false });

        window.addEventListener('touchend', () => {
            if (isDragging) {
                onDragEnd();
            }
        });
    </script>
</body>
</html>