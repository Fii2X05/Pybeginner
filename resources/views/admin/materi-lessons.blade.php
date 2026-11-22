<x-layouts.admin title="Pelajaran - {{ $module->title }}" active="materi">
    <a href="{{ route('admin.materi') }}" class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Materi Belajar
    </a>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">{{ $module->course?->title }}</p>
            <h1 class="mt-1 text-2xl font-black text-slate-900">{{ $module->title }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $module->lessons->count() }} bagian materi</p>
        </div>
        <button type="button" onclick="document.getElementById('create-lesson').showModal()" class="inline-flex items-center gap-2 self-start rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">
            <i data-lucide="plus" class="h-4 w-4"></i> Tambah Pelajaran
        </button>
    </div>
    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr><th class="px-5 py-3.5">Urutan</th><th class="px-5 py-3.5">Judul &amp; slug</th><th class="px-5 py-3.5 text-center">Latihan</th><th class="px-5 py-3.5 text-center">Status</th><th class="px-5 py-3.5 text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($module->lessons as $lesson)
                        <tr>
                            <td class="px-5 py-4 text-slate-500">#{{ $lesson->sort_order }}</td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">{{ $lesson->title }}</div>
                                <div class="font-mono text-xs text-slate-400">{{ $lesson->slug }}</div>
                            </td>
                            <td class="px-5 py-4 text-center text-slate-600">{{ $lesson->exercises_count }}</td>
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.lessons.status.update', $lesson) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="is_published" value="{{ $lesson->is_published ? 0 : 1 }}">
                                    <button type="submit" class="mx-auto block rounded-full px-2.5 py-1 text-xs font-semibold {{ $lesson->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $lesson->is_published ? 'Aktif' : 'Draft' }}</button>
                                </form>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.lessons.edit', $lesson) }}" title="Edit isi materi" class="rounded-lg p-2 text-blue-600 hover:bg-blue-50"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                                    <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" onsubmit="return confirm('Hapus pelajaran ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" @disabled($lesson->exercises_count || $lesson->progress_count) title="{{ $lesson->exercises_count || $lesson->progress_count ? 'Tidak dapat dihapus: terdapat latihan atau progres siswa' : 'Hapus pelajaran' }}" class="rounded-lg p-2 text-rose-600 hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-30"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">Belum ada pelajaran. Tambahkan bagian materi pertama untuk modul ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <dialog id="create-lesson" class="w-[calc(100%-2rem)] max-w-2xl rounded-2xl p-0 shadow-2xl backdrop:bg-slate-900/50">
        <form method="POST" action="{{ route('admin.materi.lessons.store', $module) }}" class="space-y-4 p-6">
            @csrf
            <h2 class="text-lg font-black text-slate-900">Tambah Pelajaran</h2>
            <label class="block text-sm font-semibold text-slate-700">Judul
                <input name="title" required maxlength="255" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            </label>
            <label class="block text-sm font-semibold text-slate-700">Slug (opsional)
                <input name="slug" pattern="[a-z0-9]+(-[a-z0-9]+)*" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 font-mono text-sm">
            </label>
            <label class="block text-sm font-semibold text-slate-700">Isi materi (Markdown)
                <textarea name="content" required rows="10" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 font-mono text-sm"></textarea>
            </label>
            <div class="grid grid-cols-2 gap-3">
                <label class="block text-sm font-semibold text-slate-700">Urutan
                    <input name="sort_order" type="number" min="1" value="{{ ($module->lessons->max('sort_order') ?? 0) + 1 }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
                </label>
                <label class="flex items-center gap-2 pt-6 text-sm font-semibold text-slate-700">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" class="rounded border-slate-300 text-emerald-600"> Aktif
                </label>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600">Batal</button>
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white">Tambah Pelajaran</button>
            </div>
        </form>
    </dialog>
</x-layouts.admin>
