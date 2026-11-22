@php
    $isLoggedIn = auth()->check();

    $links = $isLoggedIn
        ? [
            'beranda' => ['Beranda', route('beranda')],
            'modul'   => ['Modul Belajar', route('modul.index')],
            'latihan' => ['Latihan Coding', route('latihan.index')],
            'riwayat' => ['Riwayat Submission', route('submissions.history')],
            'bantuan' => ['Bantuan', route('support.create')],
        ]
        : [
            'beranda'      => ['Beranda', '#beranda'],
            'fitur'        => ['Fitur Unggulan', '#fitur'],
            'metode'       => ['Metode Belajar', '#metode'],
            'mulai-coding' => ['Mulai Coding', '#mulai-coding'],
        ];
@endphp

<header id="main-landing-navbar" class="fixed top-0 left-0 right-0 z-50 border-b border-emerald-100/90 bg-white/95 backdrop-blur-md shadow-xs transition-all duration-300">
    <nav class="mx-auto flex h-16 sm:h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:grid lg:grid-cols-[1fr_auto_1fr] lg:px-10">

        {{-- Logo --}}
        <a href="{{ route('beranda') }}" class="flex items-center justify-self-start group transition-transform duration-200 active:scale-95">
            <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-8 sm:h-9 w-auto transition-transform duration-200 group-hover:opacity-90">
        </a>

        {{-- Menu tengah (Teks bersih dengan garis indikator bawah tipis & halus sesuai referensi) --}}
        <div id="landing-nav-menu" class="relative hidden items-center gap-6 xl:gap-8 text-sm lg:text-base font-medium lg:flex py-1">
            {{-- Garis indikator tipis (2px) --}}
            <span id="nav-indicator"
                  class="absolute bottom-0 h-[2px] bg-emerald-600 rounded-full pointer-events-none opacity-0"></span>

            @foreach ($links as $key => [$label, $href])
                <a href="{{ $href }}" @unless ($isLoggedIn) data-spy @endunless
                   data-nav-key="{{ $key }}"
                   class="nav-tab-item relative py-2 text-slate-600 transition-colors duration-200 hover:text-emerald-700 select-none {{ $key === 'beranda' ? 'text-slate-900 font-semibold active-link' : '' }}">
                    <span class="nav-tab-text inline-block">
                        {{ $label }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Kanan --}}
        <div class="flex items-center gap-2 sm:gap-3 justify-self-end">
            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                        </svg>
                        <span>Dashboard Admin</span>
                    </a>
                @endif

                <span class="hidden text-sm font-semibold text-slate-800 xl:inline">{{ auth()->user()->name }}</span>

                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('profile') }}" title="{{ auth()->user()->role === 'admin' ? 'Dashboard Admin' : 'Profil' }}" class="shrink-0 flex items-center group">
                    @if (auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                             class="h-9 w-9 sm:h-10 sm:w-10 rounded-full object-cover ring-2 ring-emerald-500 hover:ring-4 transition duration-200"
                             onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                        <div class="hidden h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xs sm:text-sm shadow-sm hover:bg-emerald-500 transition duration-200">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @else
                        <div class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xs sm:text-sm shadow-sm hover:bg-emerald-500 transition duration-200">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </a>

                <form method="POST" action="{{ route('logout') }}" class="hidden lg:inline" data-confirm-logout onsubmit="/* showModal() */">
                    @csrf
                    <button type="submit" title="Keluar"
                            class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:border-red-200 hover:bg-red-50 hover:text-red-600 active:scale-95">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="hidden text-sm lg:text-base font-medium text-slate-700 transition hover:text-emerald-700 sm:inline active:scale-95">Masuk</a>

                <a href="{{ route('register') }}"
                   class="rounded-xl bg-yellow-400 px-3.5 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-slate-900 shadow-sm transition duration-200 hover:bg-yellow-300 active:scale-95">
                    Daftar Gratis
                </a>
            @endauth

            <button type="button" aria-label="Buka menu"
                    onclick="document.getElementById('menu-mobile').classList.toggle('hidden')"
                    class="flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-xl text-slate-700 transition hover:bg-emerald-50 active:scale-95 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </nav>

    {{-- Menu mobile --}}
    <div id="menu-mobile" class="hidden border-t border-emerald-100 bg-white px-4 sm:px-6 py-4 lg:hidden shadow-lg animate-in fade-in duration-200">
        <div class="space-y-1">
            @foreach ($links as $key => [$label, $href])
                <a href="{{ $href }}"
                   onclick="document.getElementById('menu-mobile').classList.add('hidden')"
                   class="mobile-nav-link block rounded-xl px-4 py-3 text-sm font-medium transition duration-200 active:scale-[0.98] {{ $key === 'beranda' ? 'bg-emerald-50 font-semibold text-emerald-800' : 'text-slate-700 hover:bg-emerald-50' }}">
                    {{ $label }}
                </a>
            @endforeach

            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800 transition hover:bg-emerald-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                        </svg>
                        <span>Dashboard Admin</span>
                    </a>
                @endif
                <div class="border-t border-slate-100 pt-3 mt-2">
                    <div class="px-4 py-2 text-xs font-semibold text-slate-500">
                        Masuk sebagai <span class="font-bold text-slate-800">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" data-confirm-logout>
                        @csrf
                        <button type="submit"
                                class="w-full rounded-xl px-4 py-3 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50 active:scale-[0.98]">
                            Keluar dari Akun
                        </button>
                    </form>
                </div>
            @else
                <div class="border-t border-slate-100 pt-3 mt-2 sm:hidden space-y-1">
                    <a href="{{ route('login') }}"
                       class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-emerald-50 active:scale-[0.98]">Masuk ke Akun</a>
                </div>
            @endauth
        </div>
    </div>
</header>
<x-logout-confirmation />

<style>
    /* Styling garis indikator tipis & meluncur halus */
    #nav-indicator {
        position: absolute;
        bottom: 0;
        height: 2px;
        background-color: #059669;
        border-radius: 9999px;
        pointer-events: none;
        transition: left 0.3s ease, width 0.3s ease, opacity 0.2s ease;
        will-change: left, width;
    }
</style>

@push('scripts')
<script>
    (function () {
        const navMenu = document.getElementById('landing-nav-menu');
        const indicator = document.getElementById('nav-indicator');
        const navLinks = document.querySelectorAll('.nav-tab-item');
        const sections = document.querySelectorAll('section[id]');
        const header = document.getElementById('main-landing-navbar');

        // Posisikan garis indikator tipis tepat di bawah teks
        function moveIndicatorTo(linkEl) {
            if (!linkEl || !navMenu || !indicator) return;

            const menuRect = navMenu.getBoundingClientRect();
            const textSpan = linkEl.querySelector('.nav-tab-text') || linkEl;
            const textRect = textSpan.getBoundingClientRect();

            indicator.style.left = `${textRect.left - menuRect.left}px`;
            indicator.style.width = `${textRect.width}px`;
            indicator.style.opacity = '1';
        }

        // Inisialisasi awal posisi garis
        function initIndicator() {
            const activeLink = navMenu?.querySelector('.nav-tab-item.active-link') || navLinks[0];
            if (activeLink) {
                moveIndicatorTo(activeLink);
            }
        }

        initIndicator();
        document.addEventListener('DOMContentLoaded', initIndicator);
        window.addEventListener('load', initIndicator);
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(initIndicator);
        }
        setTimeout(initIndicator, 50);
        setTimeout(initIndicator, 200);
        window.addEventListener('resize', initIndicator);

        // Hover halus: garis mengikuti link yang di-hover dan kembali ke link aktif saat keluar
        navLinks.forEach(link => {
            link.addEventListener('mouseenter', function () {
                moveIndicatorTo(this);
            });
        });

        navMenu?.addEventListener('mouseleave', function () {
            const activeLink = navMenu.querySelector('.nav-tab-item.active-link');
            if (activeLink) {
                moveIndicatorTo(activeLink);
            }
        });

        // Event Klik Teks Navbar
        navLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                const href = this.getAttribute('href');

                // Update status aktif & geser garis
                navLinks.forEach(l => {
                    l.classList.remove('text-slate-900', 'font-semibold', 'active-link');
                    l.classList.add('text-slate-600');
                });
                this.classList.remove('text-slate-600');
                this.classList.add('text-slate-900', 'font-semibold', 'active-link');
                moveIndicatorTo(this);

                // Smooth scroll ke section target
                if (href && href.startsWith('#')) {
                    const targetSection = document.querySelector(href);
                    if (targetSection) {
                        e.preventDefault();
                        const headerOffset = header ? header.offsetHeight : 80;
                        const targetY = targetSection.getBoundingClientRect().top + window.pageYOffset - headerOffset;

                        window.scrollTo({
                            top: Math.max(0, targetY),
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });

        // Scroll spy untuk memperbarui menu aktif secara otomatis saat menggulir halaman
        let isManualClick = false;
        navLinks.forEach(l => l.addEventListener('click', () => {
            isManualClick = true;
            setTimeout(() => { isManualClick = false; }, 800);
        }));

        function updateScrollSpy() {
            if (isManualClick || !sections.length) return;

            let currentId = 'beranda';
            const scrollPos = window.scrollY + 140;

            sections.forEach(sec => {
                if (scrollPos >= sec.offsetTop) {
                    currentId = sec.id;
                }
            });

            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                const isMatch = href === '#' + currentId;

                if (isMatch && !link.classList.contains('active-link')) {
                    navLinks.forEach(l => {
                        l.classList.remove('text-slate-900', 'font-semibold', 'active-link');
                        l.classList.add('text-slate-600');
                    });
                    link.classList.remove('text-slate-600');
                    link.classList.add('text-slate-900', 'font-semibold', 'active-link');
                    moveIndicatorTo(link);
                }
            });
        }

        window.addEventListener('scroll', updateScrollSpy, { passive: true });
    })();
</script>
@endpush
