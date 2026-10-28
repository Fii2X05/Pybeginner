<x-layouts.admin title="Materi Belajar - Admin" active="materi">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Materi Belajar</h1>
            <p class="text-sm text-slate-500 mt-0.5">{{ $modules->count() }} modul terdaftar</p>
        </div>
        <button type="button" onclick="document.getElementById('modal-create').classList.remove('hidden')"
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Modul
        </button>
    </div>

    {{-- Tabel Modul --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5 w-16">#</th>
                    <th class="px-5 py-3.5">Judul Modul</th>
                    <th class="px-5 py-3.5 text-center w-36">Tingkat Kesulitan</th>
                    <th class="px-5 py-3.5 text-center w-32">Bagian Isi</th>
                    <th class="px-5 py-3.5 text-center w-28">Status</th>
                    <th class="px-5 py-3.5 text-right w-48">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($modules as $module)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="px-5 py-4 font-bold text-slate-400 text-sm">
                            {{ sprintf('%02d', $module->sort_order) }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-semibold text-slate-900">{{ $module->title }}</div>
                            @if ($module->description)
                                <div class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ $module->description }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <form method="POST" action="{{ route('admin.materi.level.update', $module) }}">
                                @csrf
                                @method('PUT')
                                <select name="level" aria-label="Tingkat kesulitan {{ $module->title }}" onchange="this.form.submit()"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    <option value="Pemula" @selected(($module->level ?: 'Pemula') === 'Pemula')>Pemula</option>
                                    <option value="Menengah Bawah" @selected($module->level === 'Menengah Bawah')>Menengah Bawah</option>
                                    <option value="Menengah" @selected($module->level === 'Menengah')>Menengah</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <a href="{{ route('admin.materi.lessons', $module) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 rounded-full transition">
                                <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                                {{ $module->lessons_count ?? 0 }} Bagian
                            </a>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if ($module->is_published)
                                <span class="inline-block text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">Aktif</span>
                            @else
                                <span class="inline-block text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">Draft</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Kelola seluruh isi modul --}}
                                <a href="{{ route('admin.materi.lessons', $module) }}"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                                    Kelola Isi
                                </a>
                                {{-- Edit Modul --}}
                                <button type="button" onclick="openEdit(@json($module))"
                                        class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Modul">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                {{-- Hapus Modul --}}
                                <button type="button" data-delete-url="{{ route('admin.materi.destroy', $module) }}" data-delete-title="{{ $module->title }}" onclick="openDelete(this)"
                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Modul">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center text-slate-400 text-sm">
                            Belum ada modul. Klik "Tambah Modul" untuk membuat yang pertama.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== MODAL TAMBAH MODUL ===== --}}
    <div id="modal-create" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 {{ old('lesson_title') !== null ? '' : 'hidden' }} overflow-y-auto">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-slate-900">Tambah Modul Baru</h3>
                <button onclick="document.getElementById('modal-create').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('admin.materi.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Modul <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="Contoh: Dasar Python & Sintaks"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Level</label>
                    <select name="level" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="Pemula" @selected(old('level', 'Pemula') === 'Pemula')>Pemula</option>
                        <option value="Menengah Bawah" @selected(old('level') === 'Menengah Bawah')>Menengah Bawah</option>
                        <option value="Menengah" @selected(old('level') === 'Menengah')>Menengah</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Urutan</label>
                        <input type="number" name="sort_order" min="1" value="{{ ($modules->max('sort_order') ?? 0) + 1 }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                        <select name="is_published" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="1" @selected(old('is_published', '1') === '1')>Aktif</option>
                            <option value="0" @selected(old('is_published') === '0')>Draft</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Ringkasan singkat isi modul ini..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('description') }}</textarea>
                </div>
                <div class="border-t border-slate-100 pt-4">
                    <div class="mb-3">
                        <p class="text-sm font-bold text-slate-900">Bagian materi pertama</p>
                        <p class="mt-1 text-xs leading-5 text-slate-500">Modul berisi beberapa bagian yang tampil berurutan dalam satu halaman lengkap. Setelah modul dibuat, bagian lain bisa ditambahkan lewat Kelola Isi.</p>
                    </div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul bagian <span class="text-red-500">*</span></label>
                    <input type="text" name="lesson_title" required value="{{ old('lesson_title') }}" placeholder="Contoh: Pengenalan Variabel"
                           class="mb-3 w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('lesson_title')<p class="mb-2 text-xs text-red-600">{{ $message }}</p>@enderror
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Isi materi <span class="text-red-500">*</span></label>
                    <textarea name="lesson_content" rows="7" required placeholder="Tulis penjelasan, contoh, dan rangkuman. Markdown didukung: ## Subjudul, **teks tebal**, dan blok kode dengan ```python ... ```"
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

    {{-- ===== MODAL EDIT MODUL ===== --}}
    <div id="modal-edit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-slate-900">Edit Modul</h3>
                <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <p class="mb-4 rounded-xl bg-blue-50 px-3.5 py-3 text-xs leading-5 text-blue-800">Di sini kamu mengubah judul, deskripsi, level, urutan, dan status. Untuk mengedit teks, contoh kode, atau kesimpulan materi, buka <strong>Kelola Isi</strong> pada modul.</p>
            <form id="form-edit" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Modul <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-title" name="title" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Level</label>
                    <select id="edit-level" name="level" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="Pemula">Pemula</option>
                        <option value="Menengah Bawah">Menengah Bawah</option>
                        <option value="Menengah">Menengah</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Urutan</label>
                        <input type="number" id="edit-sort" name="sort_order" min="1" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                        <select id="edit-published" name="is_published"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="1">Aktif</option>
                            <option value="0">Draft</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea id="edit-desc" name="description" rows="2"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')"
                            class="flex-1 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL HAPUS MODUL ===== --}}
    <div id="modal-delete" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-sm p-6 shadow-xl text-center">
            <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="trash-2" class="w-6 h-6"></i>
            </div>
            <h3 class="font-bold text-slate-900 mb-1">Hapus Modul?</h3>
            <p class="text-sm text-slate-500 mb-5">
                Modul "<span id="delete-name" class="font-semibold text-slate-800"></span>" dan semua pelajaran di dalamnya akan dihapus permanen.
            </p>
            <form id="form-delete" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('modal-delete').classList.add('hidden')"
                            class="flex-1 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEdit(m) {
            document.getElementById('form-edit').action = '/admin/materi/' + m.id;
            document.getElementById('edit-title').value = m.title || '';
            document.getElementById('edit-level').value = m.level || 'Pemula';
            document.getElementById('edit-sort').value = m.sort_order || 1;
            document.getElementById('edit-published').value = m.is_published ? '1' : '0';
            document.getElementById('edit-desc').value = m.description || '';
            document.getElementById('modal-edit').classList.remove('hidden');
        }
        function openDelete(button) {
            const deleteForm = document.getElementById('form-delete');
            deleteForm.action = button.dataset.deleteUrl;
            document.getElementById('delete-name').textContent = button.dataset.deleteTitle;
            document.getElementById('modal-delete').classList.remove('hidden');
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.getElementById('modal-create').classList.add('hidden');
                document.getElementById('modal-edit').classList.add('hidden');
                document.getElementById('modal-delete').classList.add('hidden');
            }
        });
    </script>
    @endpush

</x-layouts.admin>
