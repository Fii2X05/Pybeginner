<x-layouts.app title="Modul Belajar Python - PyBeginner" active="modul">

    {{-- ===================== HEADER MODUL ===================== --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-white via-emerald-50/70 to-emerald-50">
        <div class="hidden lg:block absolute top-0 right-0 w-96 h-96 bg-yellow-100/50 rounded-full blur-3xl z-0"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 py-10">
            <div class="flex items-center gap-3 mb-4 text-xs font-semibold">
                <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                    KURIKULUM TERSTRUKTUR PEMULA
                </span>
                <span class="text-gray-400">•</span>
                <span class="text-gray-500 font-mono-code">PYTHON 3.7.7</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/learning.png') }}" alt="" class="w-24 h-24 object-contain object-top">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Modul Belajar Python</h1>
                    </div>
                </div>

                <a href="{{ route('latihan.index') }}"
                   class="inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
                    <i data-lucide="square-code" class="w-4 h-4"></i>
                    Buka Latihan Python
                </a>
            </div>

            <p class="text-gray-600 max-w-2xl mb-8">
                Pelajari konsep pemrograman Python langkah demi langkah. Dilengkapi dengan materi teori ringkas, latihan interaktif langsung di browser, dan penilaian otomatis.
            </p>

            {{-- Stat cards --}}
            @php
                $totalModul   = count($modulList ?? []);
                $modulSelesai = collect($modulList ?? [])->where('status', 'selesai')->count();
                $modulBerjalan = collect($modulList ?? [])->where('status', 'berjalan')->count();
                $modulBelum   = collect($modulList ?? [])->where('status', 'belum')->count();
                $persenSelesai = $totalModul > 0 ? (int) round($modulSelesai / $totalModul * 100) : 0;
            @endphp
            <div class="grid sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                        <i data-lucide="book-open" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 tracking-wide">TOTAL KURIKULUM</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $totalModul }} Modul</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                        <i data-lucide="badge-check" class="w-5 h-5 text-emerald-600"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 tracking-wide">MODUL SELESAI</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $modulSelesai }} <span class="text-base font-medium text-gray-400">/ {{ $totalModul }} ({{ $persenSelesai }}%)</span></p>
                    </div>
                </div>
            </div>

            {{-- Progress keseluruhan --}}
            <div>
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-gray-600">Kemajuan Keseluruhan Anda <span class="text-gray-400">• {{ $modulSelesai }} dari {{ $totalModul }} modul tuntas dipelajari</span></span>
                    <span class="font-mono-code text-emerald-700 font-semibold">{{ $persenSelesai }}% Selesai</span>
                </div>
                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-500 to-yellow-400 rounded-full transition-all duration-500" style="width: {{ $persenSelesai }}%"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== DAFTAR MODUL ===================== --}}
    <section class="bg-emerald-50 py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">

            @if (session('status'))
                <div class="mb-6 bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    <span class="font-medium text-sm">{{ session('status') }}</span>
                </div>
            @endif

            {{-- Search & filter --}}
            <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-8">
                <div class="relative flex-1">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                    <input type="text" id="cari-modul" placeholder="Cari nama modul atau topik pembelajaran..."
                           class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="flex items-center gap-2 overflow-x-auto" id="filter-status">
                    <button type="button" data-filter="semua" class="filter-btn px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold text-gray-900 whitespace-nowrap">Semua Modul</button>
                    <button type="button" data-filter="berjalan" class="filter-btn px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 whitespace-nowrap">Sedang Berjalan ({{ $modulBerjalan }})</button>
                    <button type="button" data-filter="belum" class="filter-btn px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 whitespace-nowrap">Belum Mulai ({{ $modulBelum }})</button>
                    <button type="button" data-filter="selesai" class="filter-btn px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 whitespace-nowrap">Selesai ({{ $modulSelesai }})</button>
                </div>

                <div class="relative">
                    <select id="urutkan-modul" class="appearance-none pl-4 pr-9 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="kurikulum">Urutkan: Sesuai Kurikulum</option>
                        <option value="az">Urutkan: A-Z</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>

            {{-- Grid modul dinamis --}}
            <div id="grid-modul" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 items-start">

                @foreach ($modulList as $modul)
                    <div class="modul-card bg-white rounded-2xl {{ $modul['status'] === 'berjalan' ? 'border border-emerald-300 ring-1 ring-emerald-200' : 'border border-gray-100' }} shadow-sm p-6 flex flex-col"
                         data-status="{{ $modul['status'] }}"
                         data-no="{{ $modul['no'] }}"
                         data-title="{{ Str::lower($modul['title']) }}"
                         data-search="{{ Str::lower($modul['title'] . ' ' . $modul['desc'] . ' ' . $modul['search_keywords']) }}">

                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $modul['level'] === 'Pemula' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                {{ $modul['level'] }}
                            </span>

                            {{-- Notif status di pojok kanan kartu --}}
                            @if ($modul['status'] === 'selesai')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                                    <i data-lucide="check" class="w-3 h-3"></i> Selesai
                                </span>
                            @elseif ($modul['status'] === 'berjalan')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-700 bg-yellow-50 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> Sedang Berjalan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                                    <i data-lucide="lock" class="w-3 h-3"></i> Belum Mulai
                                </span>
                            @endif
                        </div>

                        <p class="text-xs font-mono-code text-gray-400 mb-1">
                            MODUL {{ sprintf('%02d', $modul['no']) }}{{ $modul['status'] === 'berjalan' ? ' • MATERI AKTIF' : '' }}
                        </p>
                        <h3 class="font-bold text-gray-900 mb-2">{{ $modul['title'] }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">{!! $modul['desc_html'] !!}</p>

                        @if ($modul['status'] === 'berjalan' && !empty($modul['materiAktif']))
                            <div class="flex items-center gap-2 text-xs text-gray-600 bg-gray-50 border border-gray-100 rounded-lg px-3 py-2 mb-4">
                                <i data-lucide="play-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                {{ $modul['materiAktif'] }}
                            </div>
                        @endif

                        <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
                            <span class="flex items-center gap-1"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> {{ $modul['materi'] }} Materi</span>
                        </div>

                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="text-gray-500">
                                @if ($modul['status'] === 'selesai')
                                    {{ $modul['materi'] }} / {{ $modul['materi'] }} Materi Dibaca
                                @elseif ($modul['status'] === 'berjalan')
                                    {{ $modul['selesaiCount'] }} / {{ $modul['materi'] }} Materi Dibaca
                                @else
                                    0 / {{ $modul['materi'] }} Materi
                                @endif
                            </span>
                            <span class="font-semibold text-gray-700">{{ $modul['progress'] }}%</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mb-4">
                            @if ($modul['status'] === 'selesai')
                                <div class="h-full rounded-full bg-emerald-500" style="width: 100%"></div>
                            @elseif ($modul['status'] === 'berjalan')
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-yellow-400" style="width: {{ $modul['progress'] }}%"></div>
                            @else
                                <div class="h-full rounded-full bg-gray-200" style="width: 0%"></div>
                            @endif
                        </div>

                        {{-- Tampilan Button Dinamis Sesuai Status --}}
                        @if ($modul['status'] === 'selesai')
                            <a href="{{ $modul['url'] }}"
                               class="w-full flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm py-2.5 rounded-lg transition-colors">
                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Tinjau Ulang
                            </a>
                        @elseif ($modul['status'] === 'berjalan')
                            <a href="{{ $modul['url'] }}"
                               class="w-full flex items-center justify-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm py-2.5 rounded-lg transition-colors">
                                Lanjutkan Belajar <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        @else
                            <a href="{{ $modul['url'] }}"
                               class="w-full flex items-center justify-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold text-sm py-2.5 rounded-lg transition-colors">
                                Mulai Modul
                            </a>
                        @endif
                    </div>
                @endforeach

            </div>

            {{-- Kosong: hasil filter/pencarian tidak ada yang cocok --}}
            <div id="modul-kosong" class="hidden bg-white rounded-2xl border border-gray-100 p-10 text-center text-gray-600">
                Tidak ada modul yang cocok dengan pencarian atau filter kamu.
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        lucide.createIcons();

        // Filter status, pencarian, dan pengurutan kartu modul (di sisi browser)
        (function () {
            const grid = document.getElementById('grid-modul');
            if (!grid) return;

            const cards = Array.from(grid.querySelectorAll('.modul-card'));
            const cari = document.getElementById('cari-modul');
            const urut = document.getElementById('urutkan-modul');
            const tombol = document.querySelectorAll('.filter-btn');
            const kosong = document.getElementById('modul-kosong');
            let filterAktif = 'semua';

            function terapkan() {
                const q = cari.value.trim().toLowerCase();
                let tampil = 0;

                cards.forEach(function (c) {
                    const cocokStatus = filterAktif === 'semua' || c.dataset.status === filterAktif;
                    const cocokCari = q === '' || c.dataset.search.includes(q);
                    const lihat = cocokStatus && cocokCari;
                    c.classList.toggle('hidden', !lihat);
                    if (lihat) tampil++;
                });

                const urutan = cards.slice().sort(function (a, b) {
                    return urut.value === 'az'
                        ? a.dataset.title.localeCompare(b.dataset.title)
                        : Number(a.dataset.no) - Number(b.dataset.no);
                });
                urutan.forEach(function (c) { grid.appendChild(c); });

                kosong.classList.toggle('hidden', tampil !== 0 || cards.length === 0);
            }

            tombol.forEach(function (b) {
                b.addEventListener('click', function () {
                    filterAktif = b.dataset.filter;
                    tombol.forEach(function (x) {
                        const aktif = x === b;
                        x.classList.toggle('bg-white', aktif);
                        x.classList.toggle('border', aktif);
                        x.classList.toggle('border-gray-200', aktif);
                        x.classList.toggle('font-semibold', aktif);
                        x.classList.toggle('text-gray-900', aktif);
                        x.classList.toggle('font-medium', !aktif);
                        x.classList.toggle('text-gray-600', !aktif);
                    });
                    terapkan();
                });
            });

            cari.addEventListener('input', terapkan);
            urut.addEventListener('change', terapkan);
        })();
    </script>
    @endpush
</x-layouts.app>
