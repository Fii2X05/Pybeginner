<x-layouts.admin title="Latihan & Soal - Admin" active="latihan">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
                <span>BANK SOAL</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Latihan &amp; Soal
            </h1>
            <p class="text-sm text-slate-500 mt-1">Daftar soal coding interaktif yang dikerjakan oleh siswa pada platform.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3.5 py-1.5 rounded-xl bg-emerald-100/70 text-emerald-800 text-xs font-bold whitespace-nowrap">
                Total {{ $exercises->count() }} Latihan
            </span>
            <a href="{{ route('admin.latihan.create') }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-4 py-2 rounded-xl transition whitespace-nowrap">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Latihan
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-sm font-medium" style="padding: 0.75rem 1rem;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 text-sm font-medium" style="padding: 0.75rem 1rem;">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">

        {{-- Bilah cari & filter --}}
        <div class="flex flex-col md:flex-row md:items-center gap-3 border-b border-slate-100" style="padding: 1rem 1.5rem;">
            <div class="relative flex-1" style="min-width: 0;">
                <i data-lucide="search" class="w-4 h-4 text-slate-400" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                <input id="filter-search" type="search" placeholder="Cari judul, materi, atau slug..."
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                       style="height: 2.5rem; padding: 0 0.85rem 0 2.5rem;">
            </div>
            <div class="flex items-center gap-2">
                <select id="filter-level" aria-label="Filter tingkat"
                        class="rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        style="height: 2.5rem; padding: 0 0.75rem;">
                    <option value="">Semua tingkat</option>
                    <option value="mudah">Mudah</option>
                    <option value="sedang">Sedang</option>
                    <option value="sulit">Sulit</option>
                </select>
                <select id="filter-status" aria-label="Filter status"
                        class="rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        style="height: 2.5rem; padding: 0 0.75rem;">
                    <option value="">Semua status</option>
                    <option value="terbit">Terbit</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width: 860px; border-collapse: collapse;">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="whitespace-nowrap text-center" style="width: 1%; padding: 0.875rem 0.5rem 0.875rem 1.5rem;">No</th>
                        <th style="padding: 0.875rem 1rem;">Judul latihan</th>
                        <th class="text-center whitespace-nowrap" style="width: 1%; padding: 0.875rem 1rem;">Tingkat</th>
                        <th class="text-center whitespace-nowrap" style="width: 1%; padding: 0.875rem 1rem;">Test case</th>
                        <th class="text-center whitespace-nowrap" style="width: 1%; padding: 0.875rem 1rem;">Status</th>
                        <th class="text-right whitespace-nowrap" style="width: 1%; padding: 0.875rem 1.5rem 0.875rem 1rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="exercise-rows">
                    @forelse ($exercises as $exercise)
                        @php
                            $rawLevel = strtolower($exercise->difficulty);
                            $level = match (true) {
                                in_array($rawLevel, ['easy', 'mudah'], true) => 'mudah',
                                in_array($rawLevel, ['medium', 'sedang'], true) => 'sedang',
                                default => 'sulit',
                            };
                            $testCaseTotal = $testCaseCounts[$exercise->id] ?? 0;
                            $visibleTotal = $visibleTestCaseCounts[$exercise->id] ?? 0;
                            $canPublish = $visibleTotal > 0;
                            // Batas efektif saat kode dijalankan (sisi siswa membatasi maks 5 detik dan 250 MB).
                            $timeLimit = min(5000, max(1000, $exercise->time_limit_ms ?? 2000));
                            $memoryLimit = min(250, max(16, $exercise->memory_limit_mb ?? 128));
                            $moduleTitle = $exercise->lesson->module->title ?? 'Umum';
                            $lessonTitle = $exercise->lesson->title ?? '-';
                            $iconButton = 'inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition';
                            $btnSize = 'width: 2.25rem; height: 2.25rem; flex: none;';
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition exercise-row"
                            data-search="{{ strtolower($exercise->title . ' ' . $moduleTitle . ' ' . $lessonTitle . ' ' . $exercise->slug) }}"
                            data-level="{{ $level }}"
                            data-status="{{ $exercise->is_published ? 'terbit' : 'draft' }}">

                            {{-- Urutan --}}
                            <td class="align-middle whitespace-nowrap text-center" style="padding: 1rem 0.5rem 1rem 1.5rem;">
                                <span class="inline-flex items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-500" style="width: 2rem; height: 2rem;">
                                    {{ sprintf('%02d', $exercise->sort_order) }}
                                </span>
                            </td>

                            {{-- Judul --}}
                            <td class="align-middle" style="padding: 1rem; min-width: 16rem; overflow-wrap: anywhere;">
                                <div class="font-semibold text-slate-900 leading-snug">{{ $exercise->title }}</div>
                                <div class="mt-0.5 text-xs text-slate-500">
                                    {{ $moduleTitle }}
                                    <span class="text-slate-300" style="margin: 0 0.35rem;">&rsaquo;</span>
                                    {{ $lessonTitle }}
                                </div>
                                <div class="mt-1 flex flex-wrap items-center text-[11px] text-slate-400" style="gap: 0.375rem;">
                                    <span class="font-mono rounded bg-slate-100 text-slate-500" style="padding: 0.05rem 0.4rem;">{{ $exercise->slug }}</span>
                                    <span title="Batas efektif saat kode dijalankan">{{ $timeLimit }} ms &middot; {{ $memoryLimit }} MB</span>
                                </div>
                            </td>

                            {{-- Tingkat --}}
                            <td class="text-center align-middle whitespace-nowrap" style="padding: 1rem;">
                                @if ($level === 'mudah')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Mudah</span>
                                @elseif ($level === 'sedang')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">Sedang</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">Sulit</span>
                                @endif
                            </td>

                            {{-- Test case --}}
                            <td class="text-center align-middle whitespace-nowrap" style="padding: 1rem;">
                                <a href="{{ route('admin.latihan.test-cases', $exercise) }}"
                                   title="{{ $canPublish ? 'Kelola test case' : 'Belum ada test case yang terlihat siswa' }}"
                                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border text-xs font-semibold transition {{ $canPublish ? 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200' : 'bg-amber-50 hover:bg-amber-100 text-amber-800 border-amber-200' }}"
                                   style="height: 2.25rem; padding: 0 0.75rem; white-space: nowrap;">
                                    <i data-lucide="{{ $canPublish ? 'list-checks' : 'alert-triangle' }}" class="w-3.5 h-3.5"></i>
                                    Test case ({{ $testCaseTotal }})
                                </a>
                            </td>

                            {{-- Status --}}
                            <td class="text-center align-middle whitespace-nowrap" style="padding: 1rem;">
                                <form method="POST" action="{{ route('admin.latihan.status.update', $exercise) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="is_published" aria-label="Status {{ $exercise->title }}"
                                            @if (! $exercise->is_published && ! $canPublish) title="Tambahkan test case yang terlihat agar soal bisa diterbitkan" @endif
                                            onchange="this.form.submit()"
                                            class="rounded-lg border text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 {{ $exercise->is_published ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-500' }}"
                                            style="height: 2.25rem; min-width: 5.5rem; padding: 0 0.625rem;">
                                        <option value="1" @selected($exercise->is_published) @disabled(! $exercise->is_published && ! $canPublish)>Terbit</option>
                                        <option value="0" @selected(! $exercise->is_published)>Draft</option>
                                    </select>
                                </form>
                            </td>

                            {{-- Aksi: tiga slot ikon dengan ukuran tetap supaya lurus antar baris --}}
                            <td class="align-middle whitespace-nowrap" style="padding: 1rem 1.5rem 1rem 1rem;">
                                <div class="flex items-center justify-end" style="gap: 0.375rem;">
                                    <a href="{{ route('admin.latihan.edit', $exercise) }}" title="Edit latihan" aria-label="Edit {{ $exercise->title }}"
                                       class="{{ $iconButton }} hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600" style="{{ $btnSize }}">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>

                                    @if ($exercise->is_published)
                                        <a href="{{ route('latihan.show', $exercise->slug) }}" target="_blank" rel="noopener" title="Tinjau di halaman siswa" aria-label="Tinjau {{ $exercise->title }}"
                                           class="{{ $iconButton }} hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-600" style="{{ $btnSize }}">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                        </a>
                                    @else
                                        {{-- Slot kosong: harus inline-block agar width/height berlaku --}}
                                        <span aria-hidden="true" style="display: inline-block; {{ $btnSize }}"></span>
                                    @endif

                                    <form method="POST" action="{{ route('admin.latihan.destroy', $exercise) }}" style="margin: 0; flex: none;"
                                          onsubmit="return confirm('Hapus latihan ini? Test case dan riwayat submission siswa untuk soal ini ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus latihan" aria-label="Hapus {{ $exercise->title }}"
                                                class="{{ $iconButton }} hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600" style="{{ $btnSize }}">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-400 text-sm" style="padding: 3rem 1.5rem;">
                                Belum ada latihan coding. Klik "Tambah Latihan" atau jalankan seeder latihan.
                            </td>
                        </tr>
                    @endforelse

                    {{-- Muncul saat hasil pencarian/filter kosong --}}
                    <tr id="no-result-row" style="display: none;">
                        <td colspan="6" class="text-center text-slate-400 text-sm" style="padding: 3rem 1.5rem;">
                            Tidak ada latihan yang cocok. Coba ubah kata kunci atau filter.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 bg-slate-50/60 text-xs text-slate-500" style="padding: 0.75rem 1.5rem;">
            Menampilkan <span id="visible-count" class="font-semibold text-slate-700">{{ $exercises->count() }}</span>
            dari {{ $exercises->count() }} latihan
        </div>
    </div>

    <script>
        (function () {
            const search = document.getElementById('filter-search');
            const level = document.getElementById('filter-level');
            const status = document.getElementById('filter-status');
            const rows = Array.from(document.querySelectorAll('.exercise-row'));
            const empty = document.getElementById('no-result-row');
            const counter = document.getElementById('visible-count');
            if (!search || !rows.length) return;

            function apply() {
                const q = search.value.trim().toLowerCase();
                let shown = 0;
                rows.forEach(function (row) {
                    const ok =
                        (!q || row.dataset.search.indexOf(q) !== -1) &&
                        (!level.value || row.dataset.level === level.value) &&
                        (!status.value || row.dataset.status === status.value);
                    row.style.display = ok ? '' : 'none';
                    if (ok) shown++;
                });
                counter.textContent = shown;
                empty.style.display = shown === 0 ? '' : 'none';
            }

            search.addEventListener('input', apply);
            level.addEventListener('change', apply);
            status.addEventListener('change', apply);
        })();
    </script>
</x-layouts.admin>