@php
    $isEditing = $module !== null;
    $action = $isEditing ? route('admin.materi.update', $module) : route('admin.materi.store');
    $inputClass = 'w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100';
@endphp

<x-layouts.admin :title="($isEditing ? 'Edit' : 'Tambah') . ' Modul - Admin'" active="materi">
    <a href="{{ route('admin.materi') }}" class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Materi Belajar
    </a>
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-900">{{ $isEditing ? 'Edit Modul' : 'Tambah Modul' }}</h1>
        <p class="mt-1 text-sm text-slate-500">Atur informasi modul dan status publikasinya.</p>
    </div>

    <form method="POST" action="{{ $action }}" class="max-w-4xl space-y-5 rounded-2xl border border-slate-100 bg-white p-5 shadow-xs sm:p-7">
        @csrf
        @if ($isEditing) @method('PUT') @endif
        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <div>
            <label for="course_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Kursus</label>
            <select id="course_id" name="course_id" required class="{{ $inputClass }}">
                <option value="">Pilih kursus</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected((string) old('course_id', $module?->course_id) === (string) $course->id)>{{ $course->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="title" class="mb-1.5 block text-sm font-semibold text-slate-700">Judul modul</label>
                <input id="title" name="title" required maxlength="255" value="{{ old('title', $module?->title) }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="slug" class="mb-1.5 block text-sm font-semibold text-slate-700">Slug {{ $isEditing ? '(kosongkan agar tidak berubah)' : '(opsional)' }}</label>
                <input id="slug" name="slug" maxlength="255" value="{{ old('slug', $module?->slug) }}" pattern="[a-z0-9]+(-[a-z0-9]+)*" class="{{ $inputClass }} font-mono">
            </div>
        </div>
        <div>
            <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi</label>
            <textarea id="description" name="description" rows="4" class="{{ $inputClass }}">{{ old('description', $module?->description) }}</textarea>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="sort_order" class="mb-1.5 block text-sm font-semibold text-slate-700">Urutan</label>
                <input id="sort_order" name="sort_order" type="number" min="1" value="{{ old('sort_order', $module?->sort_order) }}" class="{{ $inputClass }}">
            </div>
            <div class="flex items-end pb-2">
                <input type="hidden" name="is_published" value="0">
                <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $module?->is_published ?? false)) class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    Aktifkan modul
                </label>
            </div>
        </div>
        <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
            <a href="{{ route('admin.materi') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600">Batal</a>
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">{{ $isEditing ? 'Simpan Perubahan' : 'Buat Modul' }}</button>
        </div>
    </form>
</x-layouts.admin>
