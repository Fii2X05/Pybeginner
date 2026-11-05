<x-layouts.admin title="Edit Pelajaran - Admin" active="materi">
    <a href="{{ route('admin.materi.lessons', $module) }}" class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke {{ $module->title }}
    </a>
    <div class="mb-6">
        <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">{{ $module->course?->title }} / {{ $module->title }}</p>
        <h1 class="mt-1 text-2xl font-black text-slate-900">Edit Pelajaran</h1>
        <p class="mt-1 text-sm text-slate-500">Konten disimpan sebagai Markdown; HTML mentah akan dihapus saat ditampilkan untuk siswa.</p>
    </div>
    <form method="POST" action="{{ route('admin.lessons.update', $lesson) }}" class="max-w-4xl space-y-5 rounded-2xl border border-slate-100 bg-white p-5 shadow-xs sm:p-7">
        @csrf @method('PUT')
        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="block text-sm font-semibold text-slate-700">Judul
                <input name="title" required maxlength="255" value="{{ old('title', $lesson->title) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm">
            </label>
            <label class="block text-sm font-semibold text-slate-700">Slug (kosongkan agar tidak berubah)
                <input name="slug" value="{{ old('slug', $lesson->slug) }}" pattern="[a-z0-9]+(-[a-z0-9]+)*" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono text-sm">
            </label>
        </div>
        <label class="block text-sm font-semibold text-slate-700">Isi materi (Markdown)
            <textarea name="content" required rows="24" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono text-sm">{{ old('content', $lesson->content) }}</textarea>
        </label>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <label class="block text-sm font-semibold text-slate-700">Urutan
                <input name="sort_order" type="number" min="1" value="{{ old('sort_order', $lesson->sort_order) }}" class="mt-1 w-32 rounded-xl border border-slate-200 px-3 py-2.5 text-sm">
            </label>
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $lesson->is_published)) class="rounded border-slate-300 text-emerald-600"> Terbitkan
            </label>
        </div>
        <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
            <a href="{{ route('admin.materi.lessons', $module) }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600">Batal</a>
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">Simpan Materi</button>
        </div>
    </form>
</x-layouts.admin>
