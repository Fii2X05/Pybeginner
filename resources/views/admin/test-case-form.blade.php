@php
    $testCase = $testCase ?? null;
    $method = $method ?? 'POST';
    $heading = $heading ?? 'Tambah Test Case';
    $inputClass = 'w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500';
@endphp

<x-layouts.admin :title="$heading" active="latihan">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs text-slate-400 mb-5">
        <a href="{{ route('admin.latihan') }}" class="hover:text-emerald-600 font-semibold">Latihan &amp; Soal</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{ route('admin.latihan.test-cases', $exercise) }}" class="hover:text-emerald-600 font-semibold">{{ $exercise->title }}</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-700 font-semibold">{{ $heading }}</span>
    </nav>

    <div class="mb-6">
        <h1 class="text-xl font-black text-slate-900">{{ $heading }}</h1>
        <p class="text-sm text-slate-500 mt-0.5">Soal: {{ $exercise->title }}</p>
    </div>

    <form action="{{ $action }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5 max-w-3xl">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div>
            <label for="input" class="block text-xs font-semibold text-slate-700 mb-1">Input (opsional)</label>
            <textarea id="input" name="input" rows="3" spellcheck="false" class="{{ $inputClass }}">{{ old('input', $testCase->input ?? '') }}</textarea>
            <p class="text-[11px] text-slate-400 mt-1">Satu baris untuk tiap pemanggilan input(). Contoh dua bilangan: tulis 3 lalu 5 di baris berikutnya.</p>
        </div>

        <div>
            <label for="expected_output" class="block text-xs font-semibold text-slate-700 mb-1">Output yang diharapkan <span class="text-red-500">*</span></label>
            <textarea id="expected_output" name="expected_output" rows="3" spellcheck="false" class="{{ $inputClass }}">{{ old('expected_output', $testCase->expected_output ?? '') }}</textarea>
            <p class="text-[11px] text-slate-400 mt-1">Persis seperti yang dicetak program. Wajib diisi kecuali kamu memakai kode assertion di bawah.</p>
        </div>

        <details class="rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3" @if (old('assertion_code', $testCase->assertion_code ?? '')) open @endif>
            <summary class="cursor-pointer text-xs font-semibold text-slate-700">Lanjutan: kode assertion (opsional)</summary>
            <div class="mt-3">
                <textarea id="assertion_code" name="assertion_code" rows="4" spellcheck="false" class="{{ $inputClass }}"
                          placeholder="assert jumlah(2, 3) == 5">{{ old('assertion_code', $testCase->assertion_code ?? '') }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">Dipakai untuk soal berbasis fungsi. Kode ini dijalankan setelah kode siswa, dan jika diisi maka input dan output di atas tidak dipakai untuk menilai.</p>
            </div>
        </details>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="weight" class="block text-xs font-semibold text-slate-700 mb-1">Bobot <span class="text-red-500">*</span></label>
                <input id="weight" type="number" name="weight" min="1" max="100" required
                       value="{{ old('weight', $testCase->weight ?? 1) }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="sort_order" class="block text-xs font-semibold text-slate-700 mb-1">Urutan</label>
                <input id="sort_order" type="number" name="sort_order" min="1"
                       value="{{ old('sort_order', $testCase->sort_order ?? '') }}" placeholder="Otomatis" class="{{ $inputClass }}">
            </div>
            <div class="flex items-end">
                <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 pb-2.5">
                    <input type="hidden" name="is_hidden" value="0">
                    <input type="checkbox" name="is_hidden" value="1" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                           @checked((bool) old('is_hidden', $testCase->is_hidden ?? false))>
                    Sembunyikan dari siswa
                </label>
            </div>
        </div>
        <p class="text-[11px] text-slate-400">Test case yang tidak disembunyikan tampil sebagai "Contoh Uji Coba". Test case tersembunyi hanya dipakai saat penilaian akhir.</p>

        <div class="flex items-center justify-end gap-2 pt-2">
            <a href="{{ route('admin.latihan.test-cases', $exercise) }}"
               class="inline-flex items-center gap-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm px-4 py-2.5 rounded-xl transition">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Test Case
            </button>
        </div>
    </form>
</x-layouts.admin>