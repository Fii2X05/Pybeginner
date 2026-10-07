@props(['active' => ''])

@php
    $links = [
        'beranda'  => ['Beranda', route('beranda')],
        'modul'    => ['Modul Belajar', route('modul.index')],
        'latihan'  => ['Latihan Coding', route('latihan.index')],
        'riwayat'  => ['Riwayat Submission', route('submissions.history')],
    ];
    $nama = auth()->check() ? auth()->user()->name : 'Sukma Ananda';
@endphp

<header class="sticky top-0 z-30 border-b border-slate-200 bg-white">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-6">
        <div class="flex items-center gap-8">
            <a href="{{ route('beranda') }}" class="text-lg font-extrabold text-emerald-700">PyBeginner</a>
            <nav class="hidden gap-6 text-sm font-medium md:flex">
                @foreach ($links as $key => [$label, $url])
                    <a href="{{ $url }}"
                       class="border-b-2 py-5 {{ $active === $key ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-slate-600 hover:text-emerald-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </div>
        <div class="flex items-center gap-3 text-sm font-medium text-slate-700">
            <span>{{ $nama }}</span>
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-700 text-xs font-bold text-white">
                {{ strtoupper(substr($nama, 0, 1)) }}
            </span>
        </div>
    </div>
</header>