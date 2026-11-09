@props(['active' => '', 'guest' => false])

@php
    $links = [
        'beranda' => ['Beranda', route('beranda')],
        'modul'   => ['Modul Belajar', route('modul.index')],
        'latihan' => ['Latihan Coding', route('latihan.index')],
        'riwayat' => ['Riwayat Submission', route('submissions.history')],
    ];
    $nama = auth()->check() ? auth()->user()->name : 'Sukma Ananda';
@endphp

<header class="sticky top-0 z-50 border-b border-emerald-100 bg-white shadow-sm">
    <nav class="mx-auto flex h-16 sm:h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:grid lg:grid-cols-[1fr_auto_1fr] lg:px-10">

        {{-- Logo --}}
        <a href="{{ route('beranda') }}" class="flex items-center justify-self-start">
            <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-8 sm:h-9 w-auto">
        </a>

        {{-- Menu tengah (desktop) --}}
        <div class="hidden items-center gap-6 xl:gap-10 text-sm lg:text-base font-medium lg:flex">
            @foreach ($links as $key => [$label, $url])
                <a href="{{ $url }}"
                   class="border-b-2 py-2 transition {{ $active === $key
                        ? 'border-emerald-600 font-semibold text-slate-900'
                        : 'border-transparent text-slate-600 hover:border-emerald-200 hover:text-emerald-700' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Kanan --}}
        <div class="flex items-center gap-2 sm:gap-3 justify-self-end">
            @auth
                <span class="hidden text-sm font-semibold text-slate-800 xl:inline">{{ auth()->user()->name }}</span>

                <a href="{{ route('profile') }}" title="Profil" class="shrink-0 flex items-center">
                    @if (auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                             class="h-9 w-9 sm:h-10 sm:w-10 rounded-full object-cover ring-2 ring-emerald-500 hover:ring-4 transition"
                             onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                        <div class="hidden h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xs sm:text-sm shadow-sm hover:bg-emerald-500 transition">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @else
                        <div class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xs sm:text-sm shadow-sm hover:bg-emerald-500 transition">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </a>

                <form method="POST" action="{{ route('logout') }}" class="hidden lg:inline" data-confirm-logout>
                    @csrf
                    <button type="submit" title="Keluar"
                            class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="hidden text-sm lg:text-base font-medium text-slate-700 transition hover:text-emerald-700 sm:inline">Masuk</a>
                <a href="{{ route('register') }}"
                   class="rounded-xl bg-yellow-400 px-3.5 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-yellow-300">
                    Daftar Gratis
                </a>
            @endauth

            <button type="button" aria-label="Buka menu"
                    onclick="document.getElementById('menu-mobile').classList.toggle('hidden')"
                    class="flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-xl text-slate-700 transition hover:bg-emerald-50 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </nav>

    {{-- Menu mobile --}}
    <div id="menu-mobile" class="hidden border-t border-emerald-100 bg-white px-4 sm:px-6 py-4 lg:hidden">
        @auth
            <p class="mb-3 text-xs sm:text-sm font-medium text-slate-500">Masuk sebagai <span class="font-bold text-slate-800">{{ auth()->user()->name }}</span></p>
        @endauth
        <div class="space-y-1">
            @foreach ($links as $key => [$label, $url])
                <a href="{{ $url }}"
                   class="block rounded-xl px-4 py-3 text-sm font-medium transition {{ $active === $key
                        ? 'bg-emerald-50 font-semibold text-emerald-800'
                        : 'text-slate-700 hover:bg-emerald-50' }}">
                    {{ $label }}
                </a>
            @endforeach
            @auth
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-100 mt-2" data-confirm-logout>
                    @csrf
                    <button type="submit"
                            class="w-full rounded-xl px-4 py-3 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50">
                        Keluar dari Akun
                    </button>
                </form>
            @else
                <div class="border-t border-slate-100 pt-3 mt-2 sm:hidden space-y-1">
                    <a href="{{ route('login') }}"
                       class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-emerald-50">Masuk ke Akun</a>
                </div>
            @endauth
        </div>
    </div>
</header>