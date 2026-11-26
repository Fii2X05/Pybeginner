<x-layouts.admin title="Materi Belajar - Admin" active="materi">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
                <span>KURIKULUM &amp; SILABUS</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Materi Belajar &amp; Modul Kurikulum
            </h1>
            <p class="text-sm text-slate-500 mt-1">Daftar silabus kurikulum Python. Urutan modul dan materi telah disinkronkan sesuai urutan belajar siswa.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3.5 py-2 rounded-xl bg-emerald-100/70 text-emerald-800 text-xs font-bold">
                Total {{ $modules->count() }} Modul Terurut
            </span>
            <button type="button" onclick="document.getElementById('modal-create').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition shadow-2xs cursor-pointer">
                <i data-lucide="plus" class="h-4 w-4"></i>
                <span>Tambah Modul</span>
            </button>
            <a href="{{ route('modul.index') }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition shadow-2xs">
                <span>Tinjau di Web Siswa</span>
                <i data-lucide="external-link" class="w-3.5 h-3.5 text-slate-400"></i>
            </a>
        </div>
    </div>

    <!-- Info Banner Sinkronisasi -->
    <div class="mb-6 p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100 flex items-start gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
        </div>
        <div>
            <h4 class="text-xs font-bold text-emerald-900">Urutan Modul Terintegrasi &amp; Sinkron</h4>
            <p class="text-xs text-emerald-800/80 mt-0.5 leading-relaxed">
                Setiap modul diurutkan secara konsisten menggunakan atribut <code class="bg-emerald-100/80 px-1 py-0.5 rounded font-mono text-[11px]">sort_order</code> mulai dari Modul 01 hingga Modul 07. Urutan ini sepenuhnya cocok dengan silabus pembelajaran yang dilihat oleh siswa di halaman publik.
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
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <!-- Table of Modules -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Urutan</th>
                        <th class="py-3.5 px-6">Judul Modul</th>
                        <th class="py-3.5 px-6">Kursus</th>
                        <th class="py-3.5 px-6">Slug</th>
                        <th class="py-3.5 px-6 text-center">Jumlah Pelajaran</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($modules as $module)
                        @php
                            $routeDetail = \Illuminate\Support\Facades\Route::has(sprintf('modul.detail.%02d', $module->sort_order))
                                ? route(sprintf('modul.detail.%02d', $module->sort_order))
                                : route('modul.index');
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-mono font-bold text-emerald-700 text-sm whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-100">
                                    Modul {{ sprintf('%02d', $module->sort_order) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $module->title }}</div>
                                <div class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ $module->description }}</div>

                                <!-- Preview Daftar Pelajaran di Dalam Modul -->
                                @if ($module->lessons->isNotEmpty())
                                    <div class="mt-2.5 pt-2 border-t border-slate-100 flex flex-wrap gap-1.5 items-center">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-1">Urutan Lesson:</span>
                                        @foreach ($module->lessons as $lesson)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-md bg-slate-50 border border-slate-200/70 text-slate-600">
                                                <span class="font-bold text-emerald-700">{{ $lesson->sort_order }}.</span>
                                                <span>{{ $lesson->title }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-600 whitespace-nowrap">
                                {{ $module->course?->title ?? 'Python Dasar' }}
                            </td>
                            <td class="py-4 px-6 font-mono text-xs text-slate-500 whitespace-nowrap">
                                {{ $module->slug }}
                            </td>
                            <td class="py-4 px-6 text-center font-semibold text-slate-700 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    {{ $module->lessons_count ?? 0 }} Pelajaran
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if ($module->is_published)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.materi.lessons', $module) }}" title="Kelola Pelajaran" class="rounded-lg p-2 text-emerald-700 hover:bg-emerald-50 transition">
                                        <i data-lucide="book-open" class="h-4 w-4"></i>
                                    </a>
                                    <a href="{{ route('admin.materi.edit', $module) }}" title="Edit Modul" class="rounded-lg p-2 text-blue-600 hover:bg-blue-50 transition">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </a>
                                    <a href="{{ $routeDetail }}" target="_blank" title="Buka Modul Siswa" class="rounded-lg p-2 text-emerald-600 hover:bg-emerald-50 transition">
                                        <i data-lucide="external-link" class="h-4 w-4"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.materi.destroy', $module) }}" onsubmit="return confirm('Hapus modul ini beserta seluruh materi pembelajarannya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus modul" class="rounded-lg p-2 text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 px-6 text-center text-slate-400 text-sm">
                                Tidak ada modul ditemukan. Jalankan seeder modul terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== MODAL TAMBAH MODUL ===== --}}
    @php
        $shouldOpenCreateModal = $errors->hasAny([
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
                    <label for="create-level" class="block text-xs font-semibold text-slate-700 mb-1">Level</label>
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
                            <option value="1" @selected((string) old('is_published', '1') === '1')>Aktif</option>
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
                        <p class="mt-1 text-xs leading-5 text-slate-500">Modul berisi beberapa bagian yang tampil berurutan dalam satu halaman lengkap. Setelah modul dibuat, bagian lain bisa ditambahkan lewat Kelola Isi.</p>
                    </div>
                    <label for="create-lesson-title" class="block text-xs font-semibold text-slate-700 mb-1">Judul bagian <span class="text-red-500">*</span></label>
                    <input id="create-lesson-title" type="text" name="lesson_title" required maxlength="255" value="{{ old('lesson_title') }}" placeholder="Contoh: Pengenalan Variabel"
                           class="mb-3 w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('lesson_title')<p class="mb-2 text-xs text-red-600">{{ $message }}</p>@enderror
                    <label for="create-lesson-content" class="block text-xs font-semibold text-slate-700 mb-1">Isi materi <span class="text-red-500">*</span></label>
                    <textarea id="create-lesson-content" name="lesson_content" rows="7" required placeholder="Tulis penjelasan, contoh, dan rangkuman. Markdown didukung: ## Subjudul, **teks tebal**, dan blok kode dengan ```python ... ```"
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
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                document.getElementById('modal-create')?.classList.add('hidden');
            }
        });
    </script>
</x-layouts.admin>
