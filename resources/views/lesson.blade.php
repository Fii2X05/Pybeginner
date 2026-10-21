<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lesson->title }} - {{ $module->title }} - PyBeginner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .font-mono-code { font-family: ui-monospace, 'Fira Code', 'Courier New', monospace; }

        /* Gaya isi materi (hasil render Markdown) */
        .lesson-content { color: #374151; line-height: 1.8; font-size: 1rem; }
        .lesson-content > * + * { margin-top: 1rem; }
        .lesson-content h1 { font-size: 1.75rem; font-weight: 800; color: #111827; }
        .lesson-content h2 { font-size: 1.35rem; font-weight: 700; color: #111827; margin-top: 2rem; }
        .lesson-content h3 { font-size: 1.1rem; font-weight: 700; color: #111827; margin-top: 1.5rem; }
        .lesson-content ul { list-style: disc; padding-left: 1.5rem; }
        .lesson-content ol { list-style: decimal; padding-left: 1.5rem; }
        .lesson-content li + li { margin-top: .25rem; }
        .lesson-content a { color: #047857; text-decoration: underline; }
        .lesson-content strong { color: #111827; }
        .lesson-content blockquote { border-left: 4px solid #fcd34d; background: #fffbeb; padding: .75rem 1rem; border-radius: .5rem; color: #92400e; }
        .lesson-content :not(pre) > code { background: #eef2ff; color: #3730a3; padding: .1rem .4rem; border-radius: .375rem; font-size: .875em; font-family: ui-monospace, 'Fira Code', 'Courier New', monospace; }
        .lesson-content pre { background: #111827; color: #e5e7eb; padding: 1rem 1.25rem; border-radius: .75rem; overflow-x: auto; font-size: .875rem; line-height: 1.7; }
        .lesson-content pre code { font-family: ui-monospace, 'Fira Code', 'Courier New', monospace; background: transparent; color: inherit; padding: 0; }
        .lesson-content table { width: 100%; border-collapse: collapse; font-size: .9rem; }
        .lesson-content th, .lesson-content td { border: 1px solid #e5e7eb; padding: .5rem .75rem; text-align: left; }
        .lesson-content th { background: #f9fafb; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- ===================== NAVBAR ===================== --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-gray-100">
        <nav class="max-w-7xl mx-auto px-6 lg:px-10 h-16 flex items-center justify-between">
            <div class="flex items-center gap-10">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-8 w-auto">
                </a>

                <ul class="hidden md:flex items-center gap-7 text-sm font-medium text-gray-600">
                    <li><a href="{{ route('beranda') }}" class="hover:text-emerald-600 transition-colors">Beranda</a></li>
                    <li><a href="{{ route('modul.index') }}" class="text-gray-900 border-b-2 border-emerald-600 pb-1">Modul Belajar</a></li>
                    <li><a href="{{ route('latihan.index') }}" class="hover:text-emerald-600 transition-colors">Latihan Coding</a></li>
                    <li><a href="#" class="hover:text-emerald-600 transition-colors">Riwayat Submission</a></li>
                </ul>
            </div>

            <div class="flex items-center gap-5">
                <button type="button" class="relative text-gray-500 hover:text-gray-700">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <span class="hidden sm:inline text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Sukma Ananda' }}</span>
                <span class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center text-white">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </span>
            </div>
        </nav>
    </header>

    <main class="bg-emerald-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 py-8">

            {{-- Breadcrumb --}}
            <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
                <a href="{{ route('modul.index') }}" class="hover:text-emerald-700">Modul Belajar</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span>{{ $module->title }}</span>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-gray-900 font-medium">{{ $lesson->title }}</span>
            </nav>

            <div class="grid lg:grid-cols-[300px_1fr] gap-6 items-start">

                {{-- ===== Sidebar daftar materi ===== --}}
                <aside class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 lg:sticky lg:top-24">
                    <p class="text-xs font-mono-code text-gray-400 mb-1">MODUL</p>
                    <h2 class="font-bold text-gray-900 mb-4">{{ $module->title }}</h2>

                    <ol class="space-y-1">
                        @foreach ($lessons as $item)
                            @php
                                $aktif = $item->id === $lesson->id;
                                $selesai = ($statusMap[$item->id] ?? null) === 'completed';
                            @endphp
                            <li>
                                <a href="{{ route('lesson.show', [$course, $module, $item]) }}"
                                   class="flex items-start gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ $aktif ? 'bg-emerald-50 text-emerald-800 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                                    @if ($selesai)
                                        <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5 text-emerald-600 shrink-0"></i>
                                    @elseif ($aktif)
                                        <i data-lucide="play-circle" class="w-4 h-4 mt-0.5 text-emerald-600 shrink-0"></i>
                                    @else
                                        <i data-lucide="circle" class="w-4 h-4 mt-0.5 text-gray-300 shrink-0"></i>
                                    @endif
                                    <span>{{ $item->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ol>

                    <a href="{{ route('modul.index') }}"
                       class="mt-5 inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-emerald-700">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Semua modul
                    </a>
                </aside>

                {{-- ===== Konten materi ===== --}}
                <article class="min-w-0">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-10">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">
                                Materi {{ $lessons->search(fn ($l) => $l->id === $lesson->id) + 1 }} dari {{ $lessons->count() }}
                            </span>
                            @if ($isCompleted)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                                    <i data-lucide="check" class="w-3 h-3"></i> Selesai
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-6">{{ $lesson->title }}</h1>

                        <div class="lesson-content">
                            {!! $html !!}
                        </div>
                    </div>

                    {{-- Ajakan berlatih --}}
                    <div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p class="font-semibold text-gray-900">Siap mencoba langsung?</p>
                            <p class="text-sm text-gray-600">Praktikkan materi ini di playground atau kerjakan soal latihan.</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('playground') }}"
                               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold text-sm px-4 py-2.5 rounded-lg transition-colors">
                                <i data-lucide="terminal" class="w-4 h-4"></i> Playground
                            </a>
                            <a href="{{ route('latihan.index') }}"
                               class="inline-flex items-center gap-2 bg-gray-900 hover:bg-black text-white font-semibold text-sm px-4 py-2.5 rounded-lg transition-colors">
                                <i data-lucide="square-code" class="w-4 h-4"></i> Kerjakan Latihan
                            </a>
                        </div>
                    </div>

                    {{-- Navigasi sebelumnya / berikutnya --}}
                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                        @if ($prev)
                            <a href="{{ route('lesson.show', [$course, $module, $prev]) }}"
                               class="inline-flex items-center justify-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-sm px-5 py-2.5 rounded-lg transition-colors">
                                <i data-lucide="arrow-left" class="w-4 h-4"></i> {{ $prev->title }}
                            </a>
                        @else
                            <span></span>
                        @endif

                        @auth
                            @if (! $isCompleted)
                                <form method="POST" action="{{ route('lesson.complete', [$course, $module, $lesson]) }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm px-5 py-2.5 rounded-lg transition-colors">
                                        {{ $next ? 'Selesai & Lanjut' : 'Selesai' }}
                                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @elseif ($next)
                                <a href="{{ route('lesson.show', [$course, $module, $next]) }}"
                                   class="inline-flex items-center justify-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm px-5 py-2.5 rounded-lg transition-colors">
                                    {{ $next->title }} <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                            @else
                                <a href="{{ route('modul.index') }}"
                                   class="inline-flex items-center justify-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm px-5 py-2.5 rounded-lg transition-colors">
                                    Kembali ke Daftar Modul
                                </a>
                            @endif
                        @else
                            @if ($next)
                                <a href="{{ route('lesson.show', [$course, $module, $next]) }}"
                                   class="inline-flex items-center justify-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm px-5 py-2.5 rounded-lg transition-colors">
                                    {{ $next->title }} <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                            @else
                                <a href="{{ route('modul.index') }}"
                                   class="inline-flex items-center justify-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm px-5 py-2.5 rounded-lg transition-colors">
                                    Kembali ke Daftar Modul
                                </a>
                            @endif
                        @endauth
                    </div>

                    @guest
                        <p class="mt-4 text-sm text-gray-500">
                            <a href="{{ route('login') }}" class="text-emerald-700 font-semibold hover:underline">Masuk</a>
                            untuk menyimpan progres belajarmu.
                        </p>
                    @endguest
                </article>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
