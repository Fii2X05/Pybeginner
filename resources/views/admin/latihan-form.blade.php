@php
    $isEditing = $exercise !== null;
    $action = $isEditing ? route('admin.latihan.update', $exercise) : route('admin.latihan.store');
    $inputClass = 'w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100';
@endphp

<x-layouts.admin :title="($isEditing ? 'Edit' : 'Buat') . ' Latihan - Admin'" active="latihan">
    <a href="{{ route('admin.latihan') }}" class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Latihan &amp; Soal
    </a>
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-900">{{ $isEditing ? 'Edit Latihan' : 'Buat Latihan' }}</h1>
        <p class="mt-1 text-sm text-slate-500">Atur detail soal. Setelah disimpan sebagai draft, tambahkan test case input/output atau assertion.</p>
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
            <label for="lesson_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Materi pelajaran</label>
            <select id="lesson_id" name="lesson_id" required class="{{ $inputClass }}">
                <option value="">Pilih pelajaran</option>
                @foreach ($lessons->groupBy(fn ($lesson) => $lesson->module?->title ?? 'Tanpa Modul') as $moduleTitle => $moduleLessons)
                    <optgroup label="{{ $moduleTitle }}">
                        @foreach ($moduleLessons as $lesson)
                            <option value="{{ $lesson->id }}" @selected((string) old('lesson_id', $exercise?->lesson_id) === (string) $lesson->id)>{{ $lesson->title }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="title" class="mb-1.5 block text-sm font-semibold text-slate-700">Judul latihan</label>
                <input id="title" name="title" required maxlength="255" value="{{ old('title', $exercise?->title) }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="slug" class="mb-1.5 block text-sm font-semibold text-slate-700">Slug {{ $isEditing ? '(kosongkan agar tidak berubah)' : '(opsional)' }}</label>
                <input id="slug" name="slug" maxlength="255" value="{{ old('slug', $exercise?->slug) }}" pattern="[a-z0-9]+(-[a-z0-9]+)*" class="{{ $inputClass }} font-mono">
            </div>
        </div>
        <div>
            <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi soal</label>
            <textarea id="description" name="description" required rows="4" class="{{ $inputClass }}">{{ old('description', $exercise?->description) }}</textarea>
        </div>
        <div>
            <label for="instructions" class="mb-1.5 block text-sm font-semibold text-slate-700">Instruksi tambahan</label>
            <textarea id="instructions" name="instructions" rows="3" class="{{ $inputClass }}">{{ old('instructions', $exercise?->instructions) }}</textarea>
        </div>
        <div>
            <label for="starter_code" class="mb-1.5 block text-sm font-semibold text-slate-700">Kode awal</label>
            <textarea id="starter_code" name="starter_code" rows="6" spellcheck="false" class="{{ $inputClass }} font-mono">{{ old('starter_code', $exercise?->starter_code) }}</textarea>
        </div>
        <div class="grid gap-4 sm:grid-cols-4">
            <div>
                <label for="difficulty" class="mb-1.5 block text-sm font-semibold text-slate-700">Kesulitan</label>
                <select id="difficulty" name="difficulty" required class="{{ $inputClass }}">
                    @foreach (['easy' => 'Mudah', 'medium' => 'Sedang', 'hard' => 'Sulit'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('difficulty', $exercise?->difficulty ?? 'easy') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="time_limit_ms" class="mb-1.5 block text-sm font-semibold text-slate-700">Batas waktu (ms)</label>
                <input id="time_limit_ms" name="time_limit_ms" type="number" min="1000" max="5000" required value="{{ old('time_limit_ms', $exercise?->time_limit_ms ?? 10000) }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="memory_limit_mb" class="mb-1.5 block text-sm font-semibold text-slate-700">Memori (MB)</label>
                <input id="memory_limit_mb" name="memory_limit_mb" type="number" min="16" max="250" required value="{{ old('memory_limit_mb', $exercise?->memory_limit_mb ?? 128) }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="sort_order" class="mb-1.5 block text-sm font-semibold text-slate-700">Urutan</label>
                <input id="sort_order" name="sort_order" type="number" min="1" value="{{ old('sort_order', $exercise?->sort_order) }}" class="{{ $inputClass }}">
            </div>
        </div>

        @if ($isEditing)
            <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $exercise->is_published)) class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                Terbitkan untuk siswa
            </label>
            <div class="flex flex-wrap justify-between gap-3 border-t border-slate-100 pt-4">
                <a href="{{ route('admin.latihan.test-cases', $exercise) }}" class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 px-4 py-2.5 text-sm font-semibold text-indigo-700 hover:bg-indigo-50">
                    <i data-lucide="list-checks" class="h-4 w-4"></i> Kelola Test Case
                </a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">Simpan Perubahan</button>
            </div>
        @else
            <input type="hidden" name="is_published" value="0">
            <p class="rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-800">Latihan dibuat sebagai draft. Tambahkan sedikitnya satu test case publik sebelum menerbitkannya.</p>
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                <a href="{{ route('admin.latihan') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600">Batal</a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">Simpan &amp; Kelola Test Case</button>
            </div>
        @endif
    </form>
</x-layouts.admin>
