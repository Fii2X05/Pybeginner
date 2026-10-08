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
    <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 md:grid md:grid-cols-[1fr_auto_1fr] lg:px-10">

        {{-- Logo --}}
        <a href="{{ route('beranda') }}" class="flex items-center justify-self-start">
            <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-9 w-auto">
        </a>

        {{-- Menu tengah (desktop) --}}
        <div class="hidden items-center gap-10 text-base font-medium md:flex">
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
        <div class="flex items-center gap-3 justify-self-end">
            @if ($guest)
                <a href="{{ route('login') }}"
                   class="hidden text-base font-medium text-slate-700 transition hover:text-emerald-700 sm:inline">Masuk</a>
                <a href="{{ route('register') }}"
                   class="rounded-xl bg-yellow-400 px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-yellow-300">
                    Daftar Gratis
                </a>
            @else
                <span class="hidden text-base font-medium text-slate-900 sm:inline">{{ $nama }}</span>
            @endif

            <a href="{{ route('profile') }}" title="Profil"
               class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-600 text-white transition hover:bg-emerald-500 hover:ring-4 hover:ring-emerald-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/>
                </svg>
            </a>

            <button type="button" aria-label="Buka menu"
                    onclick="document.getElementById('menu-mobile').classList.toggle('hidden')"
                    class="flex h-11 w-11 items-center justify-center rounded-xl text-slate-700 transition hover:bg-emerald-50 md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </nav>

    {{-- Menu mobile --}}
    <div id="menu-mobile" class="hidden border-t border-emerald-100 bg-white px-6 py-4 md:hidden">
        @unless ($guest)
            <p class="mb-3 text-sm font-medium text-slate-500 sm:hidden">{{ $nama }}</p>
        @endunless
        <div class="space-y-1">
            @foreach ($links as $key => [$label, $url])
                <a href="{{ $url }}"
                   class="block rounded-xl px-4 py-3 text-sm font-medium transition {{ $active === $key
                        ? 'bg-emerald-50 font-semibold text-emerald-800'
                        : 'text-slate-700 hover:bg-emerald-50' }}">
                    {{ $label }}
                </a>
            @endforeach
            @if ($guest)
                <a href="{{ route('login') }}"
                   class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-emerald-50 sm:hidden">Masuk</a>
            @endif
        </div>
    </div>
</header>