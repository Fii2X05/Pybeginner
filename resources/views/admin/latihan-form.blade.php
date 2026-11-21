@php
    $exercise = $exercise ?? null;
    $method = $method ?? 'POST';
    $isCreate = $exercise === null;
    $heading = $heading ?? ($isCreate ? 'Tambah Latihan' : 'Edit Latihan');
    $canPublish = $canPublish ?? false;
    $lessonGroups = $lessons->groupBy(fn ($lesson) => $lesson->module->title ?? 'Tanpa Modul');
    $inputClass = 'w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500';
    $secondaryBtn = 'inline-flex items-center gap-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm px-4 py-2.5 rounded-xl transition';
    $draftBtn = 'inline-flex items-center gap-2 border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold text-sm px-5 py-2.5 rounded-xl transition';
    $primaryBtn = 'inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition';
@endphp

<x-layouts.admin :title="$heading" active="latihan">
    <x-admin.breadcrumb :items="$isCreate
        ? [['Latihan & Soal', route('admin.latihan')], [$heading, null]]
        : [['Latihan & Soal', route('admin.latihan')], [$exercise->title, route('admin.latihan.test-cases', $exercise)], ['Detail Soal', null]]" />

    <div class="mb-6">
        <h1 class="text-xl font-black text-slate-900">{{ $heading }}</h1>
        <p class="text-sm text-slate-500 mt-0.5">
            @if ($isCreate)
                Soal baru disimpan sebagai draft. Setelah itu tambahkan test case, lalu terbitkan agar tampil untuk siswa.
            @else
                Simpan sebagai draft, atau terbitkan bila soal sudah punya test case yang terlihat oleh siswa.
            @endif
        </p>
    </div>

    @if ($isCreate)
        <x-admin.stepper :current="1" />
    @else
        <x-admin.exercise-tabs :exercise="$exercise" active="detail" />
    @endif

    <x-admin.flash />

    @if ($lessons->isEmpty())
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl px-5 py-4 text-sm">
            Belum ada pelajaran, jadi latihan belum bisa dibuat.
            Tambahkan materi terlebih dahulu di menu
            <a href="{{ route('admin.materi') }}" class="font-bold underline">Materi Belajar</a>.
        </div>
    @else
        <form action="{{ $action }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif

            <div>
                <label for="lesson_id" class="block text-xs font-semibold text-slate-700 mb-1">Pelajaran <span class="text-red-500">*</span></label>
                <select id="lesson_id" name="lesson_id" required class="{{ $inputClass }}">
                    <option value="">Pilih pelajaran...</option>
                    @foreach ($lessonGroups as $moduleTitle => $group)
                        <optgroup label="{{ $moduleTitle }}">
                            @foreach ($group as $lesson)
                                <option value="{{ $lesson->id }}" @selected((string) old('lesson_id', $exercise->lesson_id ?? '') === (string) $lesson->id)>
                                    {{ $lesson->title }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="title" class="block text-xs font-semibold text-slate-700 mb-1">Judul latihan <span class="text-red-500">*</span></label>
                    <input id="title" type="text" name="title" required maxlength="255"
                           value="{{ old('title', $exercise->title ?? '') }}"
                           placeholder="Contoh: Jumlah Dua Bilangan" class="{{ $inputClass }}">
                </div>
                <div>
                    <label for="slug" class="block text-xs font-semibold text-slate-700 mb-1">Slug (opsional)</label>
                    <input id="slug" type="text" name="slug" maxlength="255"
                           value="{{ old('slug', $exercise->slug ?? '') }}"
                           placeholder="Kosongkan untuk dibuat otomatis dari judul" class="{{ $inputClass }} font-mono">
                    <p class="text-[11px] text-slate-400 mt-1">Huruf kecil, angka, dan tanda hubung. Harus unik.</p>
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi soal <span class="text-red-500">*</span></label>
                <textarea id="description" name="description" rows="4" required class="{{ $inputClass }}">{{ old('description', $exercise->description ?? '') }}</textarea>
            </div>

            <div>
                <label for="instructions" class="block text-xs font-semibold text-slate-700 mb-1">Instruksi (opsional)</label>
                <textarea id="instructions" name="instructions" rows="3" class="{{ $inputClass }}">{{ old('instructions', $exercise->instructions ?? '') }}</textarea>
            </div>

            <div>
                <label for="starter_code" class="block text-xs font-semibold text-slate-700 mb-1">Kode awal (opsional)</label>
                <textarea id="starter_code" name="starter_code" rows="6" spellcheck="false" class="{{ $inputClass }} font-mono">{{ old('starter_code', $exercise->starter_code ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="difficulty" class="block text-xs font-semibold text-slate-700 mb-1">Tingkat <span class="text-red-500">*</span></label>
                    <select id="difficulty" name="difficulty" required class="{{ $inputClass }}">
                        @foreach (['easy' => 'Mudah', 'medium' => 'Sedang', 'hard' => 'Sulit'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('difficulty', $exercise->difficulty ?? 'easy') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="sort_order" class="block text-xs font-semibold text-slate-700 mb-1">Urutan</label>
                    <input id="sort_order" type="number" name="sort_order" min="1"
                           value="{{ old('sort_order', $exercise->sort_order ?? '') }}" placeholder="Otomatis" class="{{ $inputClass }}">
                </div>
            </div>

            <p class="text-[11px] text-slate-400 -mt-2">
                Batas waktu dan memori diatur otomatis menurut tingkat: Mudah 1000 ms, Sedang 2000 ms, Sulit 3000 ms, dengan memori 128 MB.
            </p>

            @unless ($isCreate || $canPublish)
                <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-xl px-3.5 py-2.5">
                    Soal ini belum bisa diterbitkan karena belum punya test case aktif yang terlihat siswa.
                    <a href="{{ route('admin.latihan.test-cases.create', $exercise) }}" class="font-bold underline">Tambah test case</a>
                    dulu, lalu terbitkan.
                </p>
            @endunless

            {{-- Susunan tombol sama di semua form: Batal di kiri, aksi sekunder di tengah, aksi utama (hijau) di kanan --}}
            <div class="flex flex-wrap items-center justify-end gap-2 pt-2">
                <a href="{{ $isCreate ? route('admin.latihan') : route('admin.latihan.test-cases', $exercise) }}" class="{{ $secondaryBtn }}">Batal</a>

                @if ($isCreate)
                    <button type="submit" class="{{ $primaryBtn }}">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i> Simpan &amp; Lanjut ke Test Case
                    </button>
                @else
                    <button type="submit" name="is_published" value="0" class="{{ $draftBtn }}">
                        <i data-lucide="file-edit" class="w-4 h-4"></i> Simpan sebagai Draft
                    </button>
                    <button type="submit" name="is_published" value="1" class="{{ $primaryBtn }}"
                            @disabled(! $canPublish)
                            title="{{ $canPublish ? 'Simpan perubahan dan terbitkan untuk siswa' : 'Tambahkan minimal satu test case terlihat untuk menerbitkan' }}">
                        <i data-lucide="upload" class="w-4 h-4"></i> Simpan &amp; Terbitkan
                    </button>
                @endif
            </div>
        </form>
    @endif
</x-layouts.admin>