<x-layouts.app :title="$module->title.' - PyBeginner'" active="modul">
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
        <style>
            .module-prose { color: #374151; line-height: 1.85; font-size: 1rem; }
            .module-prose > * + * { margin-top: 1rem; }
            .module-prose h1, .module-prose h2, .module-prose h3 { color: #111827; font-weight: 800; line-height: 1.35; }
            .module-prose h1 { font-size: 1.65rem; }
            .module-prose h2 { font-size: 1.35rem; margin-top: 2rem; }
            .module-prose h3 { font-size: 1.1rem; margin-top: 1.5rem; }
            .module-prose ul { list-style: disc; padding-left: 1.5rem; }
            .module-prose ol { list-style: decimal; padding-left: 1.5rem; }
            .module-prose li + li { margin-top: .35rem; }
            .module-prose a { color: #047857; text-decoration: underline; }
            .module-prose strong { color: #111827; }
            .module-prose blockquote { border-left: 4px solid #fbbf24; background: #fffbeb; padding: .9rem 1rem; border-radius: .75rem; color: #92400e; }
            .module-prose blockquote:has(> p > strong:first-child) { border: 1px solid #e2e8f0; border-left: 4px solid #10b981; background: #fff; color: #334155; box-shadow: 0 2px 8px rgba(15, 23, 42, .04); }
            .module-prose :not(pre) > code { background: #eef2ff; color: #3730a3; padding: .12rem .4rem; border-radius: .35rem; font-size: .9em; }
            .module-prose pre { background: #0f172a; color: #e2e8f0; padding: 1.25rem; border-radius: .9rem; overflow-x: auto; font-size: .9rem; line-height: 1.75; }
            .module-prose pre code { color: inherit; background: transparent; padding: 0; }
            .module-prose .code-block { overflow: hidden; margin: 1rem 0; border-radius: .9rem; background: #1e293b; }
            .module-prose .code-header { display: flex; align-items: center; justify-content: space-between; background: #334155; padding: 10px 16px; }
            .module-prose .code-header span { color: #94a3b8; font-size: .75rem; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; }
            .module-prose .code-header button { border: 0; background: transparent; color: #cbd5e1; font-size: .75rem; cursor: pointer; }
            .module-prose .code-header button:hover { color: #fff; }
            .module-prose .code-block pre { margin: 0; border-radius: 0; background: #1e293b; }
            .module-prose .code-output { margin: 0; border-top: 1px solid #334155; border-radius: 0 0 .9rem .9rem; background: #0f172a; padding: 14px 20px; }
            .module-prose .code-output-label { margin: 0 0 .35rem; color: #9ca3af; font-size: .7rem; font-weight: 700; }
            .module-prose .code-output pre { margin: 0; padding: 0; overflow-x: auto; background: transparent; color: #4ade80; font-family: ui-monospace, 'Fira Code', monospace; font-size: .82rem; white-space: pre-wrap; }
            .module-prose .code-output code { padding: 0; background: transparent; color: inherit; font: inherit; }
            .module-prose table { width: 100%; border-collapse: collapse; font-size: .92rem; }
            .module-prose th, .module-prose td { border: 1px solid #e5e7eb; padding: .6rem .8rem; text-align: left; }
            .module-prose th { background: #f8fafc; font-weight: 700; }
            .module-section { scroll-margin-top: 6rem; }
        </style>
    @endpush

    <main class="min-h-screen bg-slate-50">
        <section class="relative overflow-hidden border-b border-gray-100 bg-gradient-to-b from-white via-emerald-50/70 to-emerald-50">
            <div class="absolute right-0 top-0 hidden h-96 w-96 rounded-full bg-yellow-100/50 blur-3xl lg:block"></div>
            <div class="relative z-10 mx-auto max-w-7xl px-6 py-8 lg:px-10">
                <nav class="mb-6 flex items-center gap-2 text-sm text-gray-500" aria-label="Breadcrumb">
                    <a href="{{ route('modul.index') }}" class="flex items-center gap-1 transition-colors hover:text-emerald-600"><i data-lucide="book-open" class="h-3.5 w-3.5"></i> Modul Belajar</a>
                    <i data-lucide="chevron-right" class="h-3.5 w-3.5 text-gray-400"></i>
                    <span class="font-semibold text-gray-900">Modul {{ sprintf('%02d', $no ?? $module->sort_order) }}</span>
                </nav>

                @if (session('status'))
                    <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-300 bg-emerald-100 px-4 py-3 text-emerald-800">
                        <i data-lucide="check-circle" class="h-5 w-5 shrink-0 text-emerald-600"></i>
                        <span class="text-sm font-medium">{{ session('status') }}</span>
                    </div>
                @endif

                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex-1">
                        <div class="mb-3 flex items-center gap-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ ($module->level ?? 'Pemula') === 'Pemula' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">{{ $module->level ?? 'Pemula' }}</span>
                            @if ($status === 'selesai')
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"><i data-lucide="check" class="h-3 w-3"></i>Selesai</span>
                            @elseif ($status === 'berjalan')
                                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2.5 py-1 text-xs font-semibold text-yellow-700"><span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>Sedang Berjalan</span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-500"><i data-lucide="lock" class="h-3 w-3"></i>Belum Mulai</span>
                            @endif
                            <span class="font-mono text-xs text-gray-400">MODUL {{ sprintf('%02d', $no ?? $module->sort_order) }}</span>
                        </div>
                        <h1 class="mb-3 text-2xl font-extrabold text-gray-900 sm:text-3xl">{{ $module->title }}</h1>
                        <p class="max-w-2xl text-sm leading-relaxed text-gray-600">{{ $module->description ?: 'Pelajari materi berikut secara bertahap. Setiap bagian berisi penjelasan, contoh, dan rangkuman.' }}</p>
                    </div>

                    <div class="flex shrink-0 items-center gap-3">
                        <div class="mr-3 text-right">
                            <p class="mb-1 text-xs text-gray-500">Kemajuan Modul</p>
                            <p data-module-read-count data-total-lessons="{{ $totalLessons }}" class="text-lg font-extrabold text-emerald-700">{{ $readCount }}/{{ $totalLessons }} Materi Dibaca</p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-full border-4 {{ $status === 'selesai' ? 'border-emerald-500' : ($status === 'berjalan' ? 'border-yellow-400' : 'border-gray-200') }}">
                            <span data-module-progress class="text-sm font-extrabold {{ $status === 'selesai' ? 'text-emerald-700' : ($status === 'berjalan' ? 'text-yellow-600' : 'text-gray-400') }}">{{ $progress }}%</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-6 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5"><i data-lucide="file-text" class="h-3.5 w-3.5"></i>{{ $totalLessons }} Materi</span>
                    <span class="flex items-center gap-1.5"><i data-lucide="bar-chart-3" class="h-3.5 w-3.5"></i>Level: {{ $module->level ?? 'Pemula' }}</span>
                </div>
            </div>
        </section>

        <section class="mx-auto grid max-w-7xl gap-6 px-5 py-8 sm:px-8 lg:grid-cols-[minmax(0,1fr)_260px] lg:items-start lg:px-10 lg:py-10">
            <aside class="order-2 h-fit lg:sticky lg:top-24 lg:order-2">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Peta belajar</p>
                        <h2 class="mt-1 font-extrabold text-slate-900">Isi Modul</h2>
                    </div>
                    <nav class="max-h-[55vh] space-y-1 overflow-y-auto p-2.5" aria-label="Daftar materi">
                        <a href="#ringkasan" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-800">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-800"><i data-lucide="book-open" class="h-4 w-4"></i></span>
                            Ringkasan Modul
                        </a>
                        @foreach ($lessons as $index => $lesson)
                            <a href="#materi-{{ $lesson->id }}" class="sidebar-link flex items-start gap-3 rounded-xl px-3 py-3 text-sm text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800">
                                <span data-progress-number="{{ sprintf('%02d', $index + 1) }}" class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg {{ ($statusMap[$lesson->id] ?? null) === 'completed' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500' }} text-xs font-bold">
                                    @if (($statusMap[$lesson->id] ?? null) === 'completed')<i data-lucide="check" class="h-4 w-4"></i>@else{{ sprintf('%02d', $index + 1) }}@endif
                                </span>
                                <span class="pt-1 font-medium">{{ $lesson->title }}</span>
                            </a>
                        @endforeach
                    </nav>
                    <div class="border-t border-slate-100 p-4">
                        <a href="{{ route('modul.index') }}" class="flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-emerald-700">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>Kembali ke semua modul
                        </a>
                    </div>
                </div>
            </aside>

            <div class="order-1 min-w-0 space-y-6 lg:order-1">
                <article id="ringkasan" class="module-section overflow-hidden rounded-2xl border border-emerald-100 bg-white shadow-sm">
                    <div class="border-b border-emerald-100 bg-gradient-to-r from-emerald-50 to-white px-6 py-5 sm:px-8">
                        <div class="flex items-start gap-4">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-700 text-white shadow-sm"><i data-lucide="map" class="h-5 w-5"></i></span>
                            <div><p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Sebelum mulai</p><h2 class="mt-1 text-xl font-black text-slate-900">Ringkasan Modul</h2></div>
                        </div>
                    </div>
                    <div class="grid gap-5 p-6 sm:grid-cols-2 sm:p-8">
                        <div class="rounded-xl bg-slate-50 p-5">
                            <h3 class="font-bold text-slate-900">Yang akan dipelajari</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $module->description ?: 'Ikuti bagian materi di bawah secara berurutan. Contoh kode dan penjelasan disusun agar bisa dipahami langkah demi langkah.' }}</p>
                        </div>
                        <div class="rounded-xl border border-yellow-100 bg-yellow-50/70 p-5">
                            <h3 class="flex items-center gap-2 font-bold text-slate-900"><i data-lucide="lightbulb" class="h-4 w-4 text-yellow-600"></i>Tips belajar</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Baca penjelasannya, perhatikan contoh kode, lalu coba ubah contoh tersebut di playground atau latihan.</p>
                            <a href="{{ route('latihan.index') }}" class="mt-3 inline-flex items-center gap-1.5 text-sm font-bold text-emerald-800 hover:text-emerald-950">Buka latihan <i data-lucide="arrow-up-right" class="h-4 w-4"></i></a>
                        </div>
                    </div>
                </article>

                @forelse ($lessons as $index => $lesson)
                    <article id="materi-{{ $lesson->id }}" data-progress-url="{{ route('lesson.progress', $lesson) }}" data-progress-percent="{{ $progressMap[$lesson->id] ?? 0 }}" class="module-section overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex flex-col gap-4 border-b border-slate-100 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                            <div class="flex items-start gap-4">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-lg font-black text-emerald-800">{{ sprintf('%02d', $index + 1) }}</span>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Bagian {{ $index + 1 }} dari {{ $totalLessons }}</p>
                                    <h2 class="mt-1 text-xl font-black text-slate-900 sm:text-2xl">{{ $lesson->title }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-6 sm:px-8 sm:py-8">
                            <div class="module-prose">{!! $renderedLessons[$lesson->id] !!}</div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                        <i data-lucide="file-plus-2" class="mx-auto h-9 w-9 text-slate-300"></i>
                        <h2 class="mt-3 font-bold text-slate-800">Materi modul ini belum tersedia</h2>
                        <p class="mt-1 text-sm text-slate-500">Admin dapat menambahkan bagian materi melalui Kelola Isi.</p>
                    </div>
                @endforelse

                <section class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-800 to-emerald-950 p-6 text-white shadow-sm sm:p-8">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-200"><i data-lucide="flag" class="h-4 w-4"></i>Akhir Modul</span>
                            <h2 class="mt-2 text-2xl font-black">Sampai di sini dulu!</h2>
                            <p class="mt-2 max-w-xl text-sm leading-6 text-emerald-100">Kamu sudah melihat seluruh bagian materi dalam modul ini. Lanjutkan dengan mencoba latihan agar konsepnya makin melekat.</p>
                        </div>
                        <div class="flex shrink-0 flex-col gap-3 sm:items-end">
                            @if ($lessons->isNotEmpty())
                                <form action="{{ route('module.toggle-complete', [$module->course, $module]) }}" method="POST">
                                    @csrf
                                    @if ($status === 'selesai')
                                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-2.5 text-sm font-semibold text-emerald-700 transition-colors hover:bg-emerald-100 sm:w-auto">
                                            <i data-lucide="check-circle" class="h-4 w-4 text-emerald-600"></i> Modul Selesai (Klik untuk Ulangi)
                                        </button>
                                    @else
                                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-emerald-700 sm:w-auto">
                                            <i data-lucide="check" class="h-4 w-4"></i> Tandai Modul Selesai
                                        </button>
                                    @endif
                                </form>
                            @endif
                            <a href="{{ route('latihan.index') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-yellow-400 px-5 py-3 text-sm font-extrabold text-slate-950 transition hover:bg-yellow-300">Coba latihan<i data-lucide="arrow-right" class="h-4 w-4"></i></a>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </main>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>
        <script>lucide.createIcons();</script>
        @include('components.module-edited-lessons-script')
    @endpush
</x-layouts.app>
