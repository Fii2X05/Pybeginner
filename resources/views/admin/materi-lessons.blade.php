<x-layouts.admin title="Pelajaran: {{ $module->title }} - Admin" active="materi">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-1.5 text-xs text-slate-500 mb-2">
                <a href="{{ route('admin.materi') }}" class="hover:text-emerald-700 font-medium">Materi Belajar</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-slate-700 font-semibold">Modul {{ sprintf('%02d', $module->sort_order) }}</span>
            </nav>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $module->title }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $module->lessons->count() }} bagian materi. Semua bagian tampil berurutan di halaman modul.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if ($module->course)
                <a href="{{ route('module.show', [$module->course, $module]) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl border border-emerald-200 bg-emerald-50/70 hover:bg-emerald-100 text-emerald-800 text-xs font-bold transition">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    <span>Pratinjau Modul</span>
                </a>
            @endif
            <a href="{{ route('admin.materi') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali</span>
            </a>
            <button type="button" onclick="document.getElementById('modal-create-lesson').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-2xs cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Pelajaran</span>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">#</th>
                        <th class="py-3.5 px-6">Judul Bagian Materi</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($module->lessons as $lesson)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-bold text-slate-400 font-mono text-xs">
                                #{{ $lesson->sort_order }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $lesson->title }}</div>
                                <div class="text-xs text-slate-400 font-mono mt-0.5 line-clamp-1 max-w-md">
                                    {{ Str::limit(strip_tags($lesson->content), 80) }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <form action="{{ route('admin.lessons.status.update', $lesson) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <label class="sr-only" for="lesson-status-{{ $lesson->id }}">Status {{ $lesson->title }}</label>
                                    <select id="lesson-status-{{ $lesson->id }}" name="is_published" onchange="this.form.submit()"
                                            class="px-3 py-1 rounded-lg border text-xs font-semibold cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500 {{ $lesson->is_published ? 'border-emerald-200 bg-emerald-50/70 text-emerald-700' : 'border-slate-200 bg-white text-slate-600' }}">
                                        <option value="1" @selected($lesson->is_published)>Terbit</option>
                                        <option value="0" @selected(!$lesson->is_published)>Draft</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if ($module->course)
                                        <a href="{{ route('lesson.show', [$module->course, $module, $lesson]) }}" target="_blank" title="Pratinjau bagian ini"
                                           class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.lessons.edit', $lesson) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-blue-200 bg-blue-50/60 hover:bg-blue-100 text-blue-700 text-xs font-semibold transition">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        <span>Edit Isi</span>
                                    </a>
                                    <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus pelajaran materi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus"
                                                class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition cursor-pointer">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 px-6 text-center text-slate-400 text-sm">
                                Belum ada pelajaran materi di modul ini. Klik "Tambah Pelajaran" untuk mengisi materi!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== MODAL TAMBAH PELAJARAN ===== --}}
    <div id="modal-create-lesson" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl w-full max-w-2xl p-6 shadow-xl my-8">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-slate-900">Tambah Pelajaran Materi Baru</h3>
                <button type="button" onclick="document.getElementById('modal-create-lesson').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('admin.materi.lessons.store', $module) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Bagian Materi</label>
                        <input type="text" name="title" required placeholder="Contoh: Pengenalan Variabel"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Urutan</label>
                        <input type="number" name="sort_order" min="1" value="{{ ($module->lessons->max('sort_order') ?? 0) + 1 }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-semibold text-slate-700">Isi Materi Pembelajaran (Format Markdown)</label>
                        <span class="text-[11px] text-slate-400">Mendukung ## Heading, **Tebal**, List, dan ```python Kode```</span>
                    </div>
                    <textarea name="content" rows="12" required
                              placeholder="Tulis penjelasan lengkap materi di sini...&#10;&#10;## Subjudul Materi&#10;Penjelasan konsep dasar..."
                              class="w-full px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500 leading-relaxed"></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="inline-flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" checked class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>Langsung Terbitkan</span>
                    </label>

                    <div class="flex gap-3">
                        <button type="button" onclick="document.getElementById('modal-create-lesson').classList.add('hidden')"
                                class="px-5 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                        <button type="submit"
                                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition">Simpan Materi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>