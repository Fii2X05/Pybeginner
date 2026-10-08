<x-layouts.app title="Latihan Coding" active="latihan">

    @php $badge = 'bg-emerald-100 text-emerald-800'; @endphp

    {{-- HERO --}}
    <section class="relative mb-10 overflow-hidden rounded-3xl bg-white px-8 py-8 shadow-sm ring-1 ring-emerald-100 md:px-12">
        <div class="pointer-events-none absolute -right-12 -top-12 h-52 w-52 rounded-full bg-emerald-100/70"></div>
        <div class="pointer-events-none absolute -bottom-14 right-28 h-36 w-36 rounded-full bg-yellow-100"></div>

        <div class="relative flex flex-col items-center gap-6 md:flex-row md:gap-10">
            <img src="{{ asset('images/mascot-python.png') }}" alt="Maskot PyBeginner"
                 class="h-28 w-auto drop-shadow-md transition duration-300 hover:-rotate-6 hover:scale-105 md:h-32">
            <div class="text-center md:text-left">
                <p class="text-sm font-semibold uppercase tracking-widest text-emerald-600">PyBeginner</p>
                <h1 class="mt-1 text-3xl font-extrabold text-slate-900 md:text-4xl">
                    Latihan Coding <span class="text-emerald-600">Python</span>
                </h1>
                <p class="mt-2 max-w-xl text-slate-600">
                    @if (!$level)
                        Pilih tingkat kesulitan, lalu kerjakan latihannya satu per satu.
                    @else
                        Kerjakan soal satu per satu. Pelan-pelan saja, yang penting konsisten.
                    @endif
                </p>
            </div>
        </div>
    </section>

    @if (!$level)
        {{-- PILIH TINGKAT --}}
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($levels as $key => $lv)
                <a href="{{ route('latihan.index', ['level' => $key]) }}"
                   class="group relative overflow-hidden rounded-3xl bg-white p-7 shadow-sm ring-1 ring-emerald-100 transition duration-200 hover:-translate-y-1 hover:shadow-xl hover:ring-emerald-300">

                    <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-emerald-50 transition duration-300 group-hover:scale-150"></div>

                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badge }}">{{ $lv['label'] }}</span>

                            {{-- indikator kesulitan: 1-3 batang --}}
                            <div class="flex items-end gap-1" aria-hidden="true">
                                @for ($b = 1; $b <= 3; $b++)
                                    <span class="w-1.5 rounded-full {{ $b <= $loop->iteration ? 'bg-emerald-500' : 'bg-emerald-100' }}"
                                          style="height: {{ 8 + $b * 6 }}px"></span>
                                @endfor
                            </div>
                        </div>

                        <p class="mt-5 min-h-[3rem] text-slate-600">{{ $lv['desc'] }}</p>

                        <div class="mt-6 flex items-center justify-between border-t border-emerald-50 pt-4">
                            <span class="text-sm font-semibold text-slate-700">{{ $counts[$key] ?? 0 }} soal</span>
                            <span class="inline-flex items-center gap-1 text-sm font-semibold text-emerald-700">
                                Lihat soal
                                <span class="transition group-hover:translate-x-1">&rarr;</span>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        @php $lv = $levels[$level]; @endphp

        {{-- BREADCRUMB + JUDUL --}}
        <nav class="mb-3 text-sm">
            <a href="{{ route('latihan.index') }}" class="font-medium text-emerald-700 hover:underline">&larr; Semua tingkat</a>
        </nav>

        <div class="mb-6 flex flex-wrap items-center gap-3">
            <h2 class="text-2xl font-bold text-slate-900">Tingkat {{ $lv['label'] }}</h2>
            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badge }}">{{ $exercises->count() }} soal</span>
        </div>

        {{-- PENCARIAN --}}
        <div class="relative mb-6">
            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m20 20-3.5-3.5"/>
            </svg>
            <input id="cari" type="text" placeholder="Cari latihan..."
                   class="w-full rounded-2xl border border-emerald-100 bg-white py-3 pl-12 pr-4 text-sm shadow-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
        </div>

        {{-- DAFTAR SOAL --}}
        <div class="space-y-3">
            @forelse ($exercises as $i => $ex)
                <div class="item group flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-emerald-100 transition duration-200 hover:-translate-y-0.5 hover:shadow-md hover:ring-emerald-300 sm:flex-row sm:items-center sm:justify-between"
                     data-teks="{{ strtolower($ex->title . ' ' . $ex->description) }}">

                    <div class="flex items-start gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-sm font-bold text-emerald-700 transition group-hover:bg-emerald-600 group-hover:text-white">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ $ex->title }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $ex->description }}</p>
                        </div>
                    </div>

                    <a href="{{ route('latihan.show', $ex->slug) }}"
                       class="inline-flex shrink-0 items-center justify-center gap-1 rounded-xl bg-yellow-400 px-5 py-2 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-yellow-300 hover:shadow">
                        Mulai <span class="transition group-hover:translate-x-0.5">&rarr;</span>
                    </a>
                </div>
            @empty
                <div class="rounded-2xl bg-white p-10 text-center text-slate-500 shadow-sm ring-1 ring-emerald-100">
                    Belum ada soal di tingkat ini.
                </div>
            @endforelse
        </div>

        {{-- hasil pencarian kosong --}}
        <div id="kosong" class="hidden rounded-2xl bg-white p-10 text-center text-slate-500 shadow-sm ring-1 ring-emerald-100">
            Tidak ada latihan yang cocok dengan pencarianmu.
        </div>

        @push('scripts')
        <script>
            const input = document.getElementById('cari');
            const kosong = document.getElementById('kosong');
            const items = document.querySelectorAll('.item');

            input.addEventListener('input', function () {
                const q = this.value.toLowerCase().trim();
                let tampil = 0;

                items.forEach(el => {
                    const cocok = el.dataset.teks.includes(q);
                    el.style.display = cocok ? '' : 'none';
                    if (cocok) tampil++;
                });

                kosong.classList.toggle('hidden', tampil > 0 || items.length === 0);
            });
        </script>
        @endpush
    @endif
</x-layouts.app>