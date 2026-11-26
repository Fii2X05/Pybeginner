<x-layouts.admin title="Materi Belajar - Admin" active="materi">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
                <span>KURIKULUM &amp; SILABUS</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Materi Belajar &amp; Modul Kurikulum
            </h1>
            <p class="text-sm text-slate-500 mt-1">Kelola modul pembelajaran, tautan navigasi, dan urutan materi agar selaras dengan halaman siswa.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="rounded-xl bg-emerald-100/70 px-3.5 py-2 text-xs font-bold text-emerald-800 whitespace-nowrap">
                Total {{ $modules->count() }} Modul
            </span>
            <button type="button" onclick="document.getElementById('modal-create').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-emerald-700 shadow-2xs cursor-pointer whitespace-nowrap">
                <i data-lucide="plus" class="h-4 w-4"></i>
                <span>Buat Modul</span>
            </button>
            <a href="{{ route('modul.index') }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition shadow-2xs whitespace-nowrap">
                <span>Buka di Web Siswa</span>
                <i data-lucide="external-link" class="w-3.5 h-3.5 text-slate-400"></i>
            </a>
        </div>
    </div>

    <!-- Banner Informasi Sinkronisasi -->
    <div class="mb-6 p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100 flex items-start gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
        </div>
        <div>
            <h4 class="text-xs font-bold text-emerald-900">Urutan Modul Terintegrasi &amp; Sinkron</h4>
            <p class="text-xs text-emerald-800/80 mt-0.5 leading-relaxed">
                Setiap modul ditampilkan secara berurutan sesuai nomor urutnya mulai dari Modul 01. Perubahan urutan atau status publikasi langsung tersinkron dengan halaman belajar siswa.
            </p>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-sm font-medium" style="padding: 0.75rem 1rem;">
            {{ session('status') }}
        </div>
    @endif
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
    @if ($errors->any())
        <div role="alert" class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        {{-- Bilah cari, filter, & urutkan --}}
        <div class="flex flex-col md:flex-row md:items-center gap-3 border-b border-slate-100" style="padding: 1rem 1.5rem;">
            <div class="relative flex-1" style="min-width: 0;">
                <i data-lucide="search" class="w-4 h-4 text-slate-400" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                <input id="filter-search" type="search" placeholder="Cari judul modul, pelajaran, deskripsi, atau tautan..."
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                       style="height: 2.5rem; padding: 0 0.85rem 0 2.5rem;">
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <select id="filter-status" aria-label="Filter status"
                        class="rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        style="height: 2.5rem; padding: 0 0.75rem;">
                    <option value="">Semua status</option>
                    <option value="terbit">Terbit</option>
                    <option value="draft">Draft</option>
                </select>
                <select id="filter-sort" aria-label="Urutkan modul"
                        class="rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        style="height: 2.5rem; padding: 0 0.75rem;">
                    <option value="order_asc">Urutan: 01 - Terbaru</option>
                    <option value="order_desc">Urutan: Terbaru - 01</option>
                    <option value="title_asc">Judul: A - Z</option>
                    <option value="title_desc">Judul: Z - A</option>
                    <option value="lessons_desc">Pelajaran: Terbanyak</option>
                    <option value="lessons_asc">Pelajaran: Tersedikit</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width: 780px; border-collapse: collapse;">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="whitespace-nowrap text-center" style="width: 1%; padding: 0.875rem 0.5rem 0.875rem 1.5rem;">No</th>
                        <th style="padding: 0.875rem 1rem;">Judul modul</th>
                        <th class="text-center whitespace-nowrap" style="width: 1%; padding: 0.875rem 1rem;">Pelajaran</th>
                        <th class="text-center whitespace-nowrap" style="width: 1%; padding: 0.875rem 1rem;">Status</th>
                        <th class="text-right whitespace-nowrap" style="width: 1%; padding: 0.875rem 1.5rem 0.875rem 1rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="module-rows">
                    @forelse ($modules as $module)
                        @php
                            $routeDetail = ($module->course && \Illuminate\Support\Facades\Route::has('module.show'))
                                ? route('module.show', [$module->course, $module]) : (\Illuminate\Support\Facades\Route::has(sprintf('modul.detail.\%02d',$module->sort_order))
                                    ? route(sprintf('modul.detail.%02d', $module->sort_order))
                                    : route('modul.index'));
                            $lessonTitles = $module->lessons->pluck('title')->implode(' ');$lessonsCount = $module->lessons_count ?? $module->lessons->count();
                            $iconButton = 'inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition';$btnSize = 'width: 2.25rem; height: 2.25rem; flex: none;';
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition module-row"
                            data-search="{{ strtolower($module->title . ' ' . ($module->description ?? '') . ' ' . $module->slug . ' ' .$lessonTitles) }}"
                            data-status="{{ $module->is_published ? 'terbit' : 'draft' }}"
                            data-order="{{ $module->sort_order }}"
                            data-title="{{ strtolower($module->title) }}"
                            data-lessons="{{ $lessonsCount }}">

                            {{-- Nomor Urut --}}
                            <td class="align-middle whitespace-nowrap text-center" style="padding: 1rem 0.5rem 1rem 1.5rem;">
                                <span class="inline-flex items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-500" style="width: 2rem; height: 2rem;">
                                    {{ sprintf('%02d', $module->sort_order) }}
                                </span>
                            </td>

                            {{-- Judul Modul & Daftar Pelajaran --}}
                            <td class="align-middle" style="padding: 1rem; min-width: 18rem;">
                                <a href="{{ route('admin.materi.edit', $module) }}" class="font-semibold text-slate-900 leading-snug hover:text-emerald-700 hover:underline">
                                    {{ $module->title }}
                                </a>

                                @if ($module->description)
                                    <div class="mt-0.5 text-xs text-slate-500 line-clamp-1">
                                        {{ $module->description }}
                                    </div>
                                @endif

                                <div class="mt-1 flex flex-wrap items-center text-[11px] text-slate-400" style="gap: 0.375rem;">
                                    <span class="font-mono rounded bg-slate-100 text-slate-500" style="padding: 0.05rem 0.4rem;">{{ $module->slug }}</span>
                                    @if ($module->level)
                                        <span>&middot; {{ $module->level }}</span>
                                    @endif
                                </div>

                                {{-- Daftar Pelajaran di Dalam Modul --}}
                                @if ($module->lessons->isNotEmpty())
                                    <div class="mt-2.5 pt-2 border-t border-slate-100 flex flex-wrap gap-1.5 items-center">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-0.5">Urutan Pelajaran:</span>
                                        @foreach ($module->lessons->take(4) as $lesson)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-md bg-slate-50 border border-slate-200/70 text-slate-600 max-w-[13rem] truncate" title="{{ $lesson->sort_order }}. {{$lesson->title }}">
                                                <span class="font-bold text-emerald-700 shrink-0">{{ $lesson->sort_order }}.</span>
                                                <span class="truncate">{{ $lesson->title }}</span>
                                            </span>
                                        @endforeach
                                        @if ($module->lessons->count() > 4)
                                            <a href="{{ route('admin.materi.lessons', $module) }}" class="inline-flex items-center text-[11px] font-semibold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition">
                                                +{{ $module->lessons->count() - 4 }} lainnya
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            {{-- Jumlah Pelajaran --}}
                            <td class="text-center align-middle whitespace-nowrap" style="padding: 1rem;">
                                <a href="{{ route('admin.materi.lessons', $module) }}"
                                   title="Kelola pelajaran dalam modul ini"
                                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border text-xs font-semibold transition {{ $lessonsCount > 0 ? 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200' : 'bg-amber-50 hover:bg-amber-100 text-amber-800 border-amber-200' }}"
                                   style="height: 2.25rem; padding: 0 0.75rem; white-space: nowrap;">
                                    <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                                    Pelajaran ({{ $lessonsCount }})
                                </a>
                            </td>

                            {{-- Status --}}
                            <td class="text-center align-middle whitespace-nowrap" style="padding: 1rem;">
                                <form method="POST" action="{{ route('admin.materi.status.update', $module) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="is_published" aria-label="Status {{ $module->title }}"
                                            onchange="this.form.submit()"
                                            class="rounded-lg border text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 {{ $module->is_published ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-500' }}"
                                            style="height: 2.25rem; min-width: 5.5rem; padding: 0 0.625rem;">
                                        <option value="1" @selected($module->is_published)>Terbit</option>
                                        <option value="0" @selected(! $module->is_published)>Draft</option>
                                    </select>
                                </form>
                            </td>

                            {{-- Aksi --}}
                            <td class="align-middle whitespace-nowrap" style="padding: 1rem 1.5rem 1rem 1rem;">
                                <div class="flex items-center justify-end" style="gap: 0.375rem;">
                                    <a href="{{ route('admin.materi.edit', $module) }}" title="Edit modul" aria-label="Edit {{ $module->title }}"
                                       class="{{ $iconButton }} hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600" style="{{ $btnSize }}">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>

                                    @if ($module->is_published)
                                        <a href="{{ $routeDetail }}" target="_blank" rel="noopener" title="Tinjau di halaman siswa" aria-label="Tinjau {{ $module->title }}"
                                           class="{{ $iconButton }} hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-600" style="{{ $btnSize }}">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                        </a>
                                    @else
                                        <span aria-hidden="true" style="display: inline-block; {{ $btnSize }}"></span>
                                    @endif

                                    <form method="POST" action="{{ route('admin.materi.destroy', $module) }}" style="margin: 0; flex: none;"
                                          onsubmit="return confirm('Hapus modul ini beserta seluruh materi pembelajarannya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus modul" aria-label="Hapus {{ $module->title }}"
                                                class="{{ $iconButton }} hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600 cursor-pointer" style="{{ $btnSize }}">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-400 text-sm" style="padding: 3rem 1.5rem;">
                                Belum ada modul pembelajaran. Klik "Buat Modul" atau jalankan pengisi data awal terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse

                    {{-- Muncul saat hasil pencarian/filter kosong --}}
                    <tr id="no-result-row" style="display: none;">
                        <td colspan="5" class="text-center text-slate-400 text-sm" style="padding: 3rem 1.5rem;">
                            Tidak ada modul yang cocok. Coba ubah kata kunci atau penyaring.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 bg-slate-50/60 text-xs text-slate-500" style="padding: 0.75rem 1.5rem;">
            Menampilkan <span id="visible-count" class="font-semibold text-slate-700">{{ $modules->count() }}</span>
            dari {{ $modules->count() }} modul
        </div>
    </div>

    {{-- ===== MODAL TAMBAH MODUL ===== --}}
    @php
        $shouldOpenCreateModal =$errors->hasAny([
            'title',
            'level',
            'sort_order',
            'is_published',
            'description',
            'lesson_title',
            'lesson_content',
        ]);
    @endphp
    <div id="modal-create"
         onclick="if (event.target === this) this.classList.add('hidden')"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 overflow-y-auto {{ $shouldOpenCreateModal ? '' : 'hidden' }}">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl my-auto max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-slate-900">Tambah Modul Baru</h3>
                <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('admin.materi.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="create-title" class="block text-xs font-semibold text-slate-700 mb-1">Judul Modul <span class="text-red-500">*</span></label>
                    <input id="create-title" type="text" name="title" required maxlength="255" value="{{ old('title') }}" placeholder="Contoh: Dasar Python & Sintaks"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="create-level" class="block text-xs font-semibold text-slate-700 mb-1">Tingkat</label>
                    <select id="create-level" name="level" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="Pemula" @selected(old('level', 'Pemula') === 'Pemula')>Pemula</option>
                        <option value="Menengah Bawah" @selected(old('level') === 'Menengah Bawah')>Menengah Bawah</option>
                        <option value="Menengah" @selected(old('level') === 'Menengah')>Menengah</option>
                    </select>
                    @error('level')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="create-sort-order" class="block text-xs font-semibold text-slate-700 mb-1">Urutan</label>
                        <input id="create-sort-order" type="number" name="sort_order" min="1" value="{{ old('sort_order', ($modules->max('sort_order') ?? 0) + 1) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('sort_order')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="create-is-published" class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                        <select id="create-is-published" name="is_published" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="1" @selected((string) old('is_published', '1') === '1')>Terbit</option>
                            <option value="0" @selected((string) old('is_published') === '0')>Draft</option>
                        </select>
                        @error('is_published')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="create-description" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea id="create-description" name="description" rows="2" maxlength="1000" placeholder="Ringkasan singkat isi modul ini..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="border-t border-slate-100 pt-4">
                    <div class="mb-3">
                        <p class="text-sm font-bold text-slate-900">Bagian materi pertama</p>
                        <p class="mt-1 text-xs leading-5 text-slate-500">Modul berisi beberapa bagian yang tampil berurutan dalam satu halaman lengkap. Setelah modul dibuat, bagian lain bisa ditambahkan lewat halaman Pelajaran.</p>
                    </div>
                    <label for="create-lesson-title" class="block text-xs font-semibold text-slate-700 mb-1">Judul bagian <span class="text-red-500">*</span></label>
                    <input id="create-lesson-title" type="text" name="lesson_title" required maxlength="255" value="{{ old('lesson_title') }}" placeholder="Contoh: Pengenalan Variabel"
                           class="mb-3 w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('lesson_title')<p class="mb-2 text-xs text-red-600">{{ $message }}</p>@enderror
                    <label for="create-lesson-content" class="block text-xs font-semibold text-slate-700 mb-1">Isi materi <span class="text-red-500">*</span></label>
                    <textarea id="create-lesson-content" name="lesson_content" rows="7" required placeholder="Tulis penjelasan, contoh, dan rangkuman. Format Markdown didukung: ## Subjudul, **teks tebal**, dan blok kode dengan ```python ... ```"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('lesson_content') }}</textarea>
                    @error('lesson_content')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-slate-400">Contoh kode hanya ditampilkan rapi sebagai materi; tidak perlu menjalankan kode di editor latihan.</p>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modal-create').classList.add('hidden')"
                            class="flex-1 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition">Simpan Modul</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const tbody = document.getElementById('module-rows');
            const search = document.getElementById('filter-search');
            const status = document.getElementById('filter-status');
            const sort = document.getElementById('filter-sort');
            const rows = Array.from(document.querySelectorAll('.module-row'));
            const empty = document.getElementById('no-result-row');
            const counter = document.getElementById('visible-count');

            if (tbody && search && rows.length) {
                function applyFilterAndSort() {
                    const q = search.value.trim().toLowerCase();
                    const sortMode = sort.value;

                    const sortedRows = [...rows].sort(function (a, b) {
                        if (sortMode === 'order_desc') {
                            return Number(b.dataset.order) - Number(a.dataset.order);
                        }
                        if (sortMode === 'title_asc') {
                            return a.dataset.title.localeCompare(b.dataset.title, 'id');
                        }
                        if (sortMode === 'title_desc') {
                            return b.dataset.title.localeCompare(a.dataset.title, 'id');
                        }
                        if (sortMode === 'lessons_desc') {
                            return Number(b.dataset.lessons) - Number(a.dataset.lessons);
                        }
                        if (sortMode === 'lessons_asc') {
                            return Number(a.dataset.lessons) - Number(b.dataset.lessons);
                        }
                        return Number(a.dataset.order) - Number(b.dataset.order);
                    });

                    let shown = 0;
                    sortedRows.forEach(function (row) {
                        const ok =
                            (!q || row.dataset.search.indexOf(q) !== -1) &&
                            (!status.value || row.dataset.status === status.value);
                        row.style.display = ok ? '' : 'none';
                        if (ok) shown++;
                        tbody.appendChild(row);
                    });

                    if (empty) {
                        tbody.appendChild(empty);
                        empty.style.display = shown === 0 ? '' : 'none';
                    }

                    if (counter) {
                        counter.textContent = shown;
                    }
                }

                search.addEventListener('input', applyFilterAndSort);
                status.addEventListener('change', applyFilterAndSort);
                sort.addEventListener('change', applyFilterAndSort);
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    document.getElementById('modal-create')?.classList.add('hidden');
                }
            });
        })();
    </script>
</x-layouts.admin>