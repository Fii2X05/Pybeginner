<x-layouts.admin title="Kelola Isi Modul - {{ $module->title }}" active="materi">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs text-slate-400 mb-5">
        <a href="{{ route('admin.materi') }}" class="hover:text-emerald-600 font-semibold">Materi Belajar</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-700 font-semibold">{{ $module->title }}</span>
    </nav>

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">{{ $module->title }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">{{ $module->lessons->count() }} bagian materi. Semua bagian tampil berurutan di halaman modul.</p>
        </div>
        <div class="flex items-center gap-2">
            @if ($module->course && $module->is_published)
                <a href="{{ route('module.show', [$module->course, $module]) }}" target="_blank"
                   class="inline-flex items-center gap-2 border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold text-sm px-4 py-2.5 rounded-xl transition">
                    <i data-lucide="external-link" class="w-4 h-4"></i> Pratinjau Modul
                </a>
            @endif
            <a href="{{ route('admin.materi') }}"
               class="inline-flex items-center gap-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm px-4 py-2.5 rounded-xl transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
            <button type="button" onclick="document.getElementById('modal-add').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Bagian
            </button>
        </div>
    </div>

    {{-- Tabel Pelajaran --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5 w-16">#</th>
                    <th class="px-5 py-3.5">Judul Bagian Materi</th>
                    <th class="px-5 py-3.5 text-center w-36">Status</th>
                    <th class="px-5 py-3.5 text-right w-52">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($module->lessons as $lesson)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-4 font-bold text-slate-400">#{{ $lesson->sort_order }}</td>
                        <td class="px-5 py-4">
                            <div class="font-semibold text-slate-900">{{ $lesson->title }}</div>
                            <div class="text-xs text-slate-400 mt-0.5 line-clamp-1 font-mono">
                                {{ Str::limit(strip_tags($lesson->content), 80) }}
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <form method="POST" action="{{ route('admin.lessons.status.update', $lesson) }}">
                                @csrf
                                @method('PUT')
                                <select name="is_published" aria-label="Status {{ $lesson->title }}" onchange="this.form.submit()"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs font-semibold {{ $lesson->is_published ? 'text-emerald-700' : 'text-slate-500' }} focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option value="1" @selected($lesson->is_published)>Aktif</option>
                                    <option value="0" @selected(!$lesson->is_published)>Draft</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Lihat di sisi siswa --}}
                                @if ($module->course)
                                    <a href="{{ route('module.show', [$module->course, $module]) }}#materi-{{ $lesson->id }}" target="_blank"
                                       class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Lihat bagian ini di halaman modul">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </a>
                                @endif
                                {{-- Edit (full-page editor) --}}
                                <a href="{{ route('admin.lessons.edit', $lesson) }}"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg transition">
                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                    Edit Isi
                                </a>
                                {{-- Hapus --}}
                                <button type="button" onclick="openDeleteLesson({{ $lesson->id }}, '{{ addslashes($lesson->title) }}')"
                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Pelajaran">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-16 text-center text-slate-400 text-sm">
                            Belum ada bagian materi. Klik "Tambah Bagian" untuk menambahkan materi pertama.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== MODAL TAMBAH PELAJARAN ===== --}}
    <div id="modal-add" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-slate-900">Tambah Bagian Materi</h3>
                <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('admin.materi.lessons.store', $module) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul bagian <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Pengenalan Variabel dan Tipe Data"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Urutan</label>
                        <input type="number" name="sort_order" min="1" value="{{ ($module->lessons->max('sort_order') ?? 0) + 1 }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                        <select name="is_published"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="1">Aktif</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Isi bagian materi (Markdown) <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="7" required
                              placeholder="Tulis penjelasan, contoh kode, dan rangkuman untuk bagian ini..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                            class="flex-1 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition">Simpan Bagian</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL HAPUS PELAJARAN ===== --}}
    <div id="modal-delete-lesson" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-xl text-center">
            <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="trash-2" class="w-6 h-6"></i>
            </div>
            <h3 class="font-bold text-slate-900 mb-1">Hapus Pelajaran?</h3>
            <p class="text-sm text-slate-500 mb-5">
                Pelajaran "<span id="del-lesson-name" class="font-semibold text-slate-800"></span>" akan dihapus permanen.
            </p>
            <form id="form-del-lesson" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('modal-delete-lesson').classList.add('hidden')"
                            class="flex-1 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openDeleteLesson(id, title) {
            document.getElementById('form-del-lesson').action = '/admin/pelajaran/' + id;
            document.getElementById('del-lesson-name').textContent = title;
            document.getElementById('modal-delete-lesson').classList.remove('hidden');
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.getElementById('modal-add').classList.add('hidden');
                document.getElementById('modal-delete-lesson').classList.add('hidden');
            }
        });
    </script>
    @endpush

</x-layouts.admin>
