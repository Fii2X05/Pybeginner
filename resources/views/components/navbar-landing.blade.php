@php
    $links = [
        'beranda'      => ['Beranda', '#beranda'],
        'fitur'        => ['Fitur Unggulan', '#fitur'],
        'metode'       => ['Metode Belajar', '#metode'],
        'mulai-coding' => ['Mulai Coding', '#mulai-coding'],
    ];
@endphp

<header class="sticky top-0 z-50 border-b border-emerald-100 bg-white shadow-sm">
    <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 md:grid md:grid-cols-[1fr_auto_1fr] lg:px-10">

        {{-- Logo --}}
        <a href="{{ route('beranda') }}" class="flex items-center justify-self-start">
            <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-9 w-auto">
        </a>

        {{-- Menu tengah (anchor ke section) --}}
        <div class="hidden items-center gap-10 text-base font-medium md:flex">
            @foreach ($links as $key => [$label, $href])
                <a href="{{ $href }}" data-spy
                   class="border-b-2 border-transparent py-2 text-slate-600 transition hover:border-emerald-200 hover:text-emerald-700">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Kanan --}}
        <div class="flex items-center gap-3 justify-self-end">
            @auth
                <span class="hidden text-sm font-semibold text-slate-800 sm:inline">{{ auth()->user()->name }}</span>

                <a href="{{ route('profile') }}" title="Profil" class="shrink-0">
                    @if (auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}"
                             class="h-10 w-10 rounded-full object-cover ring-2 ring-emerald-500 hover:ring-4 transition">
                    @else
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-sm shadow-sm hover:bg-emerald-500 transition">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </a>

                <form method="POST" action="{{ route('logout') }}" class="hidden sm:inline">
                    @csrf
                    <button type="submit" title="Keluar"
                            class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="hidden text-base font-medium text-slate-700 transition hover:text-emerald-700 sm:inline">Masuk</a>

                <a href="{{ route('register') }}"
                   class="rounded-xl bg-yellow-400 px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-yellow-300">
                    Daftar Gratis
                </a>
            @endauth

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
        <div class="space-y-1">
            @foreach ($links as $key => [$label, $href])
                <a href="{{ $href }}"
                   onclick="document.getElementById('menu-mobile').classList.add('hidden')"
                   class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-emerald-50">
                    {{ $label }}
                </a>
            @endforeach

            @auth
                <div class="border-t border-slate-100 pt-3 mt-2">
                    <div class="px-4 py-2 text-xs font-semibold text-slate-500">
                        Masuk sebagai <span class="font-bold text-slate-800">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full rounded-xl px-4 py-3 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50">
                            Keluar dari Akun
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}"
                   class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-emerald-50 sm:hidden">Masuk</a>
                <a href="{{ route('register') }}"
                   class="block rounded-xl px-4 py-3 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50 sm:hidden">Daftar Gratis</a>
            @endauth
        </div>
    </div>
</header>

@push('scripts')
<script>
    // Scroll spy: tandai menu sesuai section yang sedang dilihat
    (function () {
        const sections = document.querySelectorAll('section[id]');
        const links = document.querySelectorAll('[data-spy]');
        const on  = ['border-emerald-600', 'font-semibold', 'text-slate-900'];
        const off = ['border-transparent', 'text-slate-600'];

        function update() {
            let current = 'beranda';
            const pos = window.scrollY + 140;
            sections.forEach(s => { if (pos >= s.offsetTop) current = s.id; });

            links.forEach(l => {
                const aktif = l.getAttribute('href') === '#' + current;
                l.classList.remove(...(aktif ? off : on));
                l.classList.add(...(aktif ? on : off));
            });
        }

        window.addEventListener('scroll', update, { passive: true });
        update();
    })();
</script>
@endpush