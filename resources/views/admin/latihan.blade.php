<x-layouts.admin title="Latihan & Soal - Admin" active="latihan">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
                <span>MANAJEMEN BANK SOAL</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Latihan &amp; Bank Soal Coding
            </h1>
            <p class="text-sm text-slate-500 mt-1">Kelola latihan interaktif, slug navigasi, limit memori runtime Judge0, dan urutan soal.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="rounded-xl bg-emerald-100/70 px-3.5 py-2 text-xs font-bold text-emerald-800">
                Total {{ $exercises->count() }} Latihan
            </span>
            <a href="{{ route('admin.latihan.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-emerald-700 shadow-2xs">
                <i data-lucide="plus" class="h-4 w-4"></i>
                <span>Buat Latihan</span>
            </a>
            <a href="{{ route('latihan.index') }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition shadow-2xs">
                <span>Buka di Web Siswa</span>
                <i data-lucide="external-link" class="w-3.5 h-3.5 text-slate-400"></i>
            </a>
        </div>
    </div>

    <!-- Banner Edukasi: Slug, Navigasi, & Memori Tujuan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- Info Slug & Navigasi -->
        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                <i data-lucide="link" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-blue-900">Pengaruh Slug pada Navigasi</h4>
                <p class="text-xs text-blue-800/80 mt-0.5 leading-relaxed">
                    Siswa mengakses soal melalui rute <code class="bg-blue-100/80 px-1 py-0.5 rounded font-mono text-[11px]">/latihan/{slug}</code>. Jika terdapat dua soal dengan <strong>slug yang sama</strong>, sistem hanya akan membuka soal yang pertama ditemukan, sehingga soal kedua <strong>tidak akan bisa diakses</strong>. Pastikan setiap slug unik!
                </p>
            </div>
        </div>

        <!-- Info Memori Tujuan Judge0 -->
        <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                <i data-lucide="cpu" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-emerald-900">Memori Tujuan &amp; Batas Waktu</h4>
                <p class="text-xs text-emerald-800/80 mt-0.5 leading-relaxed">
                    <strong>Memori Tujuan</strong> adalah batas alokasi RAM sandbox compiler Judge0 (standar 128 MB). Jika kode siswa melampaui memori ini, autograder akan mengembalikan status <em>Memory Limit Exceeded (MLE)</em>. Batas waktu standar adalah 2000 ms - 10000 ms.
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Peringatan Jika Ada Slug Duplikat -->
    @if (!empty($duplicateSlugs))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 mt-0.5">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-rose-900">Peringatan: Ditemukan Slug Duplikat!</h4>
                <p class="text-xs text-rose-800/90 mt-0.5 leading-relaxed">
                    Slug berikut digunakan lebih dari satu latihan: <code class="bg-rose-200/60 px-1 py-0.5 rounded font-mono font-bold">{{ implode(', ', $duplicateSlugs) }}</code>. Harap ubah salah satu slug melalui tombol <strong>Edit</strong> agar kedua soal dapat dibuka secara normal oleh siswa.
                </p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <!-- Table of Exercises -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Urutan</th>
                        <th class="py-3.5 px-6">Judul Latihan &amp; Slug</th>
                        <th class="py-3.5 px-6">Modul / Pelajaran</th>
                        <th class="py-3.5 px-6 text-center">Tingkat</th>
                        <th class="py-3.5 px-6 text-center">Test Case</th>
                        <th class="py-3.5 px-6 text-center">Batas Memori &amp; Waktu</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($exercises as $exercise)
                        @php
                            $isDuplicateSlug = in_array($exercise->slug, $duplicateSlugs ?? []);
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-mono font-bold text-slate-600 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-700 font-bold text-xs">
                                    #{{ sprintf('%02d', $exercise->sort_order) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <span>{{ $exercise->title }}</span>
                                    @if ($isDuplicateSlug)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200" title="Slug ini bentrok dengan soal lain!">
                                            <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                            <span>Duplikat</span>
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs font-mono {{ $isDuplicateSlug ? 'text-rose-600 font-bold' : 'text-slate-400' }} mt-0.5">
                                    /latihan/{{ $exercise->slug }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600 text-xs">
                                <div class="font-semibold text-slate-800">{{ $exercise->lesson?->title ?? 'Pelajaran Umum' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $exercise->lesson?->module?->title ?? 'Python Dasar' }}</div>
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if (strtolower($exercise->difficulty) === 'easy')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Pemula
                                    </span>
                                @elseif (strtolower($exercise->difficulty) === 'medium')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                        Menengah
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                        Sulit
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold">
                                    <i data-lucide="check-square" class="w-3 h-3"></i>
                                    <span>{{ $exercise->test_cases_count ?? 0 }} Test Case</span>
                                </span>
                                <div class="text-[10px] text-slate-400 mt-1">{{ $exercise->submissions_count ?? 0 }} submission</div>
                            </td>
                            <td class="py-4 px-6 text-center text-xs text-slate-600 whitespace-nowrap">
                                <div class="font-bold text-slate-800 font-mono">
                                    {{ $exercise->memory_limit_mb ?? 128 }} MB
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono">
                                    {{ ($exercise->time_limit_ms ?? 2000) / 1000 }} detik ({{ $exercise->time_limit_ms ?? 2000 }}ms)
                                </div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.latihan.status.update', $exercise) }}">
                                    @csrf
                                    @method('PUT')
                                    <label class="sr-only" for="exercise-status-{{ $exercise->id }}">Status {{ $exercise->title }}</label>
                                    <select id="exercise-status-{{ $exercise->id }}" name="is_published" onchange="this.form.submit()" class="mx-auto block rounded-full border-0 px-2.5 py-1 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 {{ $exercise->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        <option value="1" @selected($exercise->is_published)>Terbit</option>
                                        <option value="0" @selected(! $exercise->is_published)>Draft</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.latihan.test-cases', $exercise) }}" title="Kelola Test Case" class="rounded-lg p-2 text-indigo-600 hover:bg-indigo-50 transition">
                                        <i data-lucide="list-checks" class="h-4 w-4"></i>
                                    </a>
                                    <a href="{{ route('admin.latihan.edit', $exercise) }}" title="Edit Latihan" class="rounded-lg p-2 text-blue-600 hover:bg-blue-50 transition">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </a>
                                    @if ($exercise->is_published)
                                        <a href="{{ route('latihan.show', $exercise->slug) }}" target="_blank" rel="noopener" title="Pratinjau Soal Siswa" class="rounded-lg p-2 text-emerald-600 hover:bg-emerald-50 transition">
                                            <i data-lucide="external-link" class="h-4 w-4"></i>
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.latihan.destroy', $exercise) }}" onsubmit="return confirm('Hapus latihan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" @disabled($exercise->submissions_count || $exercise->test_case_progress_count) title="{{ $exercise->submissions_count || $exercise->test_case_progress_count ? 'Tidak dapat dihapus karena sudah digunakan siswa' : 'Hapus latihan' }}" class="rounded-lg p-2 text-rose-600 hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-30 transition">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 px-6 text-center text-slate-400 text-sm">
                                Belum ada latihan coding. Tambahkan latihan baru dengan tombol Buat Latihan di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
