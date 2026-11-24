<x-layouts.app title="Latihan Coding" active="latihan">
    <div class="mx-auto max-w-5xl">

        @php
            // Gaya per tingkat (class ditulis lengkap supaya terbaca Tailwind)
            $theme = [
                'easy'   => ['chip' => 'bg-emerald-100 text-emerald-800', 'bar' => 'bg-emerald-500', 'ring' => 'hover:ring-emerald-300', 'cta' => 'bg-emerald-600 text-white hover:bg-emerald-500', 'bars' => 1],
                'medium' => ['chip' => 'bg-amber-100 text-amber-800',     'bar' => 'bg-amber-500',   'ring' => 'hover:ring-amber-300',   'cta' => 'border border-amber-400 text-amber-700 hover:bg-amber-50', 'bars' => 2],
                'hard'   => ['chip' => 'bg-rose-100 text-rose-800',       'bar' => 'bg-rose-500',    'ring' => 'hover:ring-rose-300',    'cta' => 'border border-rose-400 text-rose-700 hover:bg-rose-50', 'bars' => 3],
            ];
            $solvedIds    = $solvedIds ?? [];
            $solvedCounts = $solvedCounts ?? collect();
            $totalAll     = (int) $counts->sum();
            $totalSolved  = count($solvedIds);
            $totalLeft    = max(0, $totalAll - $totalSolved);
            $percentAll   = $totalAll > 0 ? (int) round($totalSolved / $totalAll * 100) : 0;
            $firstName    = auth()->check() ? strtok(auth()->user()->name, ' ') : null;
        @endphp

        {{-- HERO --}}
        <section class="relative mb-6 overflow-hidden rounded-3xl bg-white px-5 py-6 shadow-sm ring-1 ring-emerald-100 sm:px-8 sm:py-8 md:px-12">
            <div class="pointer-events-none absolute -right-12 -top-12 h-52 w-52 rounded-full bg-emerald-100/70"></div>
            <div class="pointer-events-none absolute -bottom-14 right-28 h-36 w-36 rounded-full bg-yellow-100"></div>

            <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div class="text-center md:text-left">
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-emerald-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> PyBeginner Practice
                    </span>
                    <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 md:text-5xl">
                        Latihan Coding <span class="text-emerald-600">Python</span>
                    </h1>
                    <p class="mt-3 max-w-xl text-slate-600">
                        @if (!$level)
                            Pilih tingkat kesulitan, pecahkan studi kasus langsung dari browser, dan tingkatkan logika kodingmu selangkah demi selangkah.
                        @else
                            Kerjakan soal satu per satu. Pelan-pelan saja, yang penting konsisten.
                        @endif
                    </p>
                    <div class="mt-4 flex flex-wrap justify-center gap-2 text-xs font-medium text-slate-600 md:justify-start">
                        <span class="rounded-full border border-emerald-100 bg-white px-3 py-1">Auto Grading Cepat</span>
                        <span class="rounded-full border border-emerald-100 bg-white px-3 py-1">Python 3</span>
                        <span class="rounded-full border border-emerald-100 bg-white px-3 py-1">Hasil Langsung</span>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-3 md:justify-end">
                    <div class="hidden max-w-[210px] rounded-2xl rounded-br-sm bg-emerald-50 p-3 text-xs leading-relaxed text-slate-600 shadow-sm ring-1 ring-emerald-100 sm:block">
                        <p class="mb-1 text-[10px] font-bold uppercase tracking-widest text-emerald-700">Maskot PyBo</p>
                        &ldquo;Halo{{ $firstName ? ' ' . $firstName : '' }}! Siap mengasah logika kodingmu hari ini? Mulai dari level Pemula ya!&rdquo;
                    </div>
                    <img src="{{ asset('images/mascot-python.png') }}" alt="Maskot PyBeginner"
                         class="h-28 w-auto drop-shadow-md transition duration-300 hover:-rotate-6 hover:scale-105 md:h-32">
                </div>
            </div>
        </section>

        @if (!$level)
            {{-- STATISTIK --}}
            <div class="mb-10 grid grid-cols-2 gap-4 lg:grid-cols-4">
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-emerald-100">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Total Soal</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $totalAll }} <span class="text-sm font-medium text-slate-400">soal</span></p>
                    <p class="mt-1 text-xs text-slate-500">Terbagi dalam {{ count($levels) }} tingkat</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-emerald-100">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-emerald-600">Terselesaikan</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $totalSolved }} <span class="text-sm font-medium text-slate-400">soal</span></p>
                    <p class="mt-1 text-xs text-slate-500">{{ $percentAll }}% pencapaian belajar</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-emerald-100">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-amber-600">Belum Selesai</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $totalLeft }} <span class="text-sm font-medium text-slate-400">soal</span></p>
                    <p class="mt-1 text-xs text-slate-500">Siap untuk kamu taklukkan</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-emerald-100">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Total Progress</p>
                        <span class="text-xs font-bold text-emerald-700">{{ $percentAll }}%</span>
                    </div>
                    <div class="mt-4 h-2 overflow-hidden rounded-full bg-emerald-100">
                        <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ $percentAll }}%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">
                        @auth
                            {{ $totalLeft > 0 ? 'Terus semangat, masih ada ' . $totalLeft . ' soal lagi!' : 'Hebat, semua soal sudah selesai!' }}
                        @else
                            Masuk untuk menyimpan progresmu.
                        @endauth
                    </p>
                </div>
            </div>

            {{-- PILIH TINGKAT --}}
            <div class="mb-5">
                <h2 class="text-xl font-bold text-slate-900">Tingkat Kesulitan</h2>
                <p class="text-sm text-slate-500">Selesaikan berurutan untuk fondasi pemrograman yang kuat dan kokoh.</p>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($levels as $key => $lv)
                    @php
                        $t      = $theme[$key] ?? $theme['easy'];
                        $total  = (int) ($counts[$key] ?? 0);
                        $done   = (int) ($solvedCounts[$key] ?? 0);
                        $pct    = $total > 0 ? (int) round($done / $total * 100) : 0;
                        $cta    = $total === 0 ? 'Belum Tersedia' : ($done === 0 ? 'Mulai Latihan' : ($done >= $total ? 'Ulangi Latihan' : 'Lanjutkan Latihan'));
                    @endphp
                    <a href="{{ route('latihan.index', ['level' => $key]) }}"
                       class="group relative flex flex-col overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-emerald-100 transition duration-200 hover:-translate-y-1 hover:shadow-xl {{ $t['ring'] }}">

                        <div class="flex items-center justify-between">
                            <span class="rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wider {{ $t['chip'] }}">{{ $lv['label'] }}</span>
                            <div class="flex items-end gap-1" aria-hidden="true">
                                @for ($b = 1; $b <= 3; $b++)
                                    <span class="w-1.5 rounded-full {{ $b <= $t['bars'] ? $t['bar'] : 'bg-slate-200' }}" style="height: {{ 8 + $b * 5 }}px"></span>
                                @endfor
                            </div>
                        </div>

                        <p class="mt-5 min-h-[3rem] text-base font-semibold leading-snug text-slate-800">{{ $lv['desc'] }}</p>

                        <div class="mt-6">
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <span>Kemajuan Modul</span>
                                <span class="font-semibold text-slate-700">{{ $done }} dari {{ $total }} selesai ({{ $pct }}%)</span>
                            </div>
                            <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $t['bar'] }}" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500">
                            <span>{{ $total }} soal</span>
                            <span>Tingkat {{ $loop->iteration }} dari {{ count($levels) }}</span>
                        </div>

                        <span class="mt-4 inline-flex w-full items-center justify-center gap-1 rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $t['cta'] }}">
                            {{ $cta }} <span class="transition group-hover:translate-x-1">&rarr;</span>
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- TIPS --}}
            <section class="mt-10 overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-900 to-emerald-700 p-6 text-white shadow-sm sm:p-8">
                <p class="text-[11px] font-bold uppercase tracking-widest text-emerald-200">Tips Sukses Belajar</p>
                <h3 class="mt-2 text-xl font-extrabold sm:text-2xl">Ketik Manual, Hindari Copy-Paste!</h3>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-emerald-50/90">
                    Membiasakan jari mengetik sintaks Python mempercepat pembentukan ingatan otot (muscle memory) dan pemahaman mendalam tentang penulisan kode yang benar.
                </p>
            </section>
        @else
            @php
                $lv     = $levels[$level];
                $t      = $theme[$level] ?? $theme['easy'];
                $total  = $exercises->count();
                $done   = $exercises->whereIn('id', $solvedIds)->count();
                $pct    = $total > 0 ? (int) round($done / $total * 100) : 0;
            @endphp

            {{-- BREADCRUMB + JUDUL --}}
            <nav class="mb-3 text-sm">
                <a href="{{ route('latihan.index') }}" class="font-medium text-emerald-700 hover:underline">&larr; Semua tingkat</a>
            </nav>

            <div class="mb-6 flex flex-wrap items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-900">Tingkat {{ $lv['label'] }}</h2>
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $t['chip'] }}">{{ $total }} soal</span>
            </div>

            {{-- PROGRES TINGKAT --}}
            <div class="mb-6 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-emerald-100">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <p class="text-sm font-bold text-slate-800">Progres Tingkat {{ $lv['label'] }}</p>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $done }} dari {{ $total }} selesai ({{ $pct }}%)</span>
                </div>
                <div class="mt-3 h-2 overflow-hidden rounded-full bg-emerald-100">
                    <div class="h-full rounded-full {{ $t['bar'] }} transition-all" style="width: {{ $pct }}%"></div>
                </div>
            </div>

            {{-- PENCARIAN + FILTER --}}
            <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center">
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m20 20-3.5-3.5"/>
                    </svg>
                    <input id="cari" type="text" placeholder="Cari latihan berdasarkan judul atau konsep (contoh: if-else, print)..."
                           class="w-full rounded-2xl border border-emerald-100 bg-white py-3 pl-12 pr-4 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                </div>
                <div class="flex shrink-0 gap-1 rounded-2xl bg-white p-1 text-xs font-semibold shadow-sm ring-1 ring-emerald-100" id="filter-group">
                    <button type="button" data-filter="all"     class="filter-btn rounded-xl bg-emerald-600 px-3 py-2 text-white">Semua ({{ $total }})</button>
                    <button type="button" data-filter="todo"    class="filter-btn rounded-xl px-3 py-2 text-slate-600 hover:bg-emerald-50">Belum ({{ $total - $done }})</button>
                    <button type="button" data-filter="done"    class="filter-btn rounded-xl px-3 py-2 text-slate-600 hover:bg-emerald-50">Selesai ({{ $done }})</button>
                </div>
            </div>

            {{-- DAFTAR SOAL --}}
            <div class="space-y-3">
                @forelse ($exercises as $i => $ex)
                    @php $isDone = in_array($ex->id, $solvedIds); @endphp
                    <div class="item group flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-emerald-100 transition duration-200 hover:-translate-y-0.5 hover:shadow-md hover:ring-emerald-300 sm:flex-row sm:items-center sm:justify-between"
                         data-status="{{ $isDone ? 'done' : 'todo' }}"
                         data-teks="{{ strtolower($ex->title . ' ' . $ex->description) }}">

                        <div class="flex items-start gap-4">
                            @if ($isDone)
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                                </div>
                            @else
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-sm font-bold text-emerald-700 transition group-hover:bg-emerald-600 group-hover:text-white">
                                    {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                </div>
                            @endif
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="font-semibold text-slate-900">{{ $ex->title }}</p>
                                    @if ($isDone)
                                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700">Selesai</span>
                                    @else
                                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-700">Belum Dikerjakan</span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-slate-500">{{ $ex->description }}</p>
                            </div>
                        </div>

                        @if ($isDone)
                            <a href="{{ route('latihan.show', $ex->slug) }}"
                               class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-xl border border-emerald-400 px-5 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50 sm:w-auto sm:py-2">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Kerjakan Ulang
                            </a>
                        @else
                            <a href="{{ route('latihan.show', $ex->slug) }}"
                               class="inline-flex w-full shrink-0 items-center justify-center gap-1 rounded-xl bg-yellow-400 px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-yellow-300 hover:shadow sm:w-auto sm:py-2">
                                Mulai Latihan <span class="transition group-hover:translate-x-0.5">&rarr;</span>
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="rounded-2xl bg-white p-10 text-center text-slate-500 shadow-sm ring-1 ring-emerald-100">
                        Belum ada soal di tingkat ini.
                    </div>
                @endforelse
            </div>

            <div id="kosong" class="hidden rounded-2xl bg-white p-10 text-center text-slate-500 shadow-sm ring-1 ring-emerald-100">
                Tidak ada latihan yang cocok dengan pencarian atau filtermu.
            </div>

            {{-- BANTUAN --}}
            <section class="mt-8 rounded-2xl bg-gradient-to-br from-emerald-900 to-emerald-700 p-5 text-white shadow-sm sm:flex sm:items-center sm:justify-between sm:p-6">
                <div>
                    <p class="font-bold">Butuh bantuan sintaks Python atau lupa rumusnya?</p>
                    <p class="mt-1 text-sm text-emerald-100">Buka Modul Belajar untuk membaca ulang materinya sebelum mengerjakan soal.</p>
                </div>
            </section>

            @push('scripts')
            <script>
                const input  = document.getElementById('cari');
                const kosong = document.getElementById('kosong');
                const items  = document.querySelectorAll('.item');
                const btns   = document.querySelectorAll('.filter-btn');
                let filter   = 'all';

                function terapkan() {
                    const q = input.value.toLowerCase().trim();
                    let tampil = 0;

                    items.forEach(el => {
                        const cocokTeks   = el.dataset.teks.includes(q);
                        const cocokStatus = filter === 'all' || el.dataset.status === filter;
                        const cocok = cocokTeks && cocokStatus;
                        el.style.display = cocok ? '' : 'none';
                        if (cocok) tampil++;
                    });

                    kosong.classList.toggle('hidden', tampil > 0 || items.length === 0);
                }

                input.addEventListener('input', terapkan);

                btns.forEach(btn => btn.addEventListener('click', () => {
                    filter = btn.dataset.filter;
                    btns.forEach(b => {
                        const aktif = b === btn;
                        b.classList.toggle('bg-emerald-600', aktif);
                        b.classList.toggle('text-white', aktif);
                        b.classList.toggle('text-slate-600', !aktif);
                        b.classList.toggle('hover:bg-emerald-50', !aktif);
                    });
                    terapkan();
                }));
            </script>
            @endpush
        @endif

    </div>
</x-layouts.app>