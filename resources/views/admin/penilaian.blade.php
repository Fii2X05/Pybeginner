<x-layouts.admin title="Penilaian - Admin" active="penilaian">
    @php
        $hasActiveFilters = filled($filters['search'] ?? null)
            || filled($filters['exercise_id'] ?? null)
            || filled($filters['status'] ?? null)
            || (($filters['sort'] ?? 'terbaru') !== 'terbaru');
    @endphp

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
                <span>EVALUASI &amp; SKOR</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Penilaian Siswa
            </h1>
            <p class="text-sm text-slate-500 mt-1">Riwayat pengumpulan kode pemrograman dan hasil evaluasi otomatis latihan siswa.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="rounded-xl bg-emerald-100/70 px-3.5 py-2 text-xs font-bold text-emerald-800 whitespace-nowrap">
                Total {{ $submissions->total() }} Pengumpulan
            </span>
        </div>
    </div>

    <!-- Tabel Penilaian -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        {{-- Bilah cari, filter latihan/soal, status, & urutkan --}}
        <form method="GET" action="{{ route('admin.penilaian') }}"
              class="flex flex-col lg:flex-row lg:items-center gap-3 border-b border-slate-100"
              style="padding: 1rem 1.5rem;">
            <div class="relative flex-1" style="min-width: 0;">
                <i data-lucide="search" class="w-4 h-4 text-slate-400" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                <input id="filter-search" type="search" name="search" value="{{ $filters['search'] ?? '' }}"
                       placeholder="Cari nama siswa, email, atau judul latihan..."
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                       style="height: 2.5rem; padding: 0 0.85rem 0 2.5rem;">
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <select name="exercise_id" aria-label="Saring berdasarkan latihan atau soal"
                        onchange="this.form.submit()"
                        class="rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 max-w-[14rem]"
                        style="height: 2.5rem; padding: 0 0.75rem;">
                    <option value="">Semua latihan / soal</option>
                    @foreach ($exercises as $exercise)
                        <option value="{{ $exercise->id }}" @selected((string) ($filters['exercise_id'] ?? '') === (string) $exercise->id)>
                            {{ $exercise->title }}
                        </option>
                    @endforeach
                </select>

                <select name="status" aria-label="Saring berdasarkan status"
                        onchange="this.form.submit()"
                        class="rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        style="height: 2.5rem; padding: 0 0.75rem;">
                    <option value="">Semua status</option>
                    <option value="lolos" @selected(($filters['status'] ?? '') === 'lolos')>Lolos</option>
                    <option value="belum_lolos" @selected(($filters['status'] ?? '') === 'belum_lolos')>Belum Lolos</option>
                </select>

                <select name="sort" aria-label="Urutkan penilaian"
                        onchange="this.form.submit()"
                        class="rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        style="height: 2.5rem; padding: 0 0.75rem;">
                    <option value="terbaru" @selected(($filters['sort'] ?? 'terbaru') === 'terbaru')>Terbaru dikumpulkan</option>
                    <option value="terlama" @selected(($filters['sort'] ?? '') === 'terlama')>Terlama dikumpulkan</option>
                    <option value="soal_asc" @selected(($filters['sort'] ?? '') === 'soal_asc')>Latihan / Soal (A - Z)</option>
                    <option value="soal_desc" @selected(($filters['sort'] ?? '') === 'soal_desc')>Latihan / Soal (Z - A)</option>
                    <option value="skor_desc" @selected(($filters['sort'] ?? '') === 'skor_desc')>Skor Tertinggi</option>
                    <option value="skor_asc" @selected(($filters['sort'] ?? '') === 'skor_asc')>Skor Terendah</option>
                </select>

                <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition cursor-pointer"
                        style="height: 2.5rem; padding: 0 1rem;">
                    Cari
                </button>

                @if ($hasActiveFilters)
                    <a href="{{ route('admin.penilaian') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-semibold transition"
                       style="height: 2.5rem; padding: 0 0.85rem;">
                        Atur Ulang
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width: 780px; border-collapse: collapse;">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th style="padding: 0.875rem 1rem 0.875rem 1.5rem;">Siswa</th>
                        <th style="padding: 0.875rem 1rem;">Latihan / soal</th>
                        <th class="text-center whitespace-nowrap" style="width: 1%; padding: 0.875rem 1rem;">Skor</th>
                        <th class="text-center whitespace-nowrap" style="width: 1%; padding: 0.875rem 1rem;">Uji lolos</th>
                        <th class="text-center whitespace-nowrap" style="width: 1%; padding: 0.875rem 1rem;">Status</th>
                        <th class="text-right whitespace-nowrap" style="width: 1%; padding: 0.875rem 1.5rem 0.875rem 1rem;">Dikumpulkan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($submissions as $submission)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="align-middle" style="padding: 1rem 1rem 1rem 1.5rem;">
                                <div class="font-semibold text-slate-900">{{ $submission->user->name ?? 'Siswa' }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $submission->user->email ?? '-' }}</div>
                            </td>
                            <td class="align-middle" style="padding: 1rem;">
                                <div class="font-semibold text-slate-800">{{ $submission->exercise->title ?? 'Latihan' }}</div>
                                @if (!empty($submission->exercise?->slug))
                                    <div class="mt-1">
                                        <span class="font-mono text-[11px] rounded bg-slate-100 text-slate-500" style="padding: 0.05rem 0.4rem;">
                                            {{ $submission->exercise->slug }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="align-middle text-center font-bold text-base whitespace-nowrap" style="padding: 1rem;">
                                <span class="{{ ($submission->score ?? 0) >= 70 ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $submission->score ?? 0 }}
                                </span>
                            </td>
                            <td class="align-middle text-center text-xs font-mono text-slate-600 whitespace-nowrap" style="padding: 1rem;">
                                {{ $submission->passed_tests ?? 0 }} / {{ $submission->total_tests ?? 0 }}
                            </td>
                            <td class="align-middle text-center whitespace-nowrap" style="padding: 1rem;">
                                @if (in_array(strtolower((string) $submission->status), ['accepted', 'passed'], true))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Lolos
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                        Belum Lolos
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle text-right text-xs text-slate-500 whitespace-nowrap" style="padding: 1rem 1.5rem 1rem 1rem;">
                                {{ $submission->submitted_at ? $submission->submitted_at->diffForHumans() : ($submission->created_at ? $submission->created_at->diffForHumans() : '-') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-400 text-sm" style="padding: 3rem 1.5rem;">
                                @if ($hasActiveFilters)
                                    Tidak ada data penilaian yang cocok dengan kata kunci atau penyaring yang dipilih.
                                @else
                                    Belum ada riwayat pengumpulan kode siswa. Siswa yang mengerjakan latihan akan tercatat di sini secara otomatis.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-slate-500" style="padding: 0.75rem 1.5rem;">
            <div>
                Menampilkan <span class="font-semibold text-slate-700">{{ $submissions->count() }}</span>
                dari {{ $submissions->total() }} penilaian
            </div>
            @if ($submissions->hasPages())
                <div>
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>