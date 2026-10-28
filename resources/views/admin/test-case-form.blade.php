@php
    $testCase = $testCase ?? null;
    $method = $method ?? 'POST';
    $heading = $heading ?? 'Tambah Test Case';
    $jenis = old('jenis', ($testCase && $testCase->assertion_code) ? 'assertion' : 'io');
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

        {{-- Jenis test case --}}
        <fieldset>
            <legend class="block text-xs font-semibold text-slate-700 mb-2">Jenis test case <span class="text-red-500">*</span></legend>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="flex items-start gap-3 rounded-xl border border-slate-200 p-3.5 cursor-pointer hover:bg-slate-50 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50">
                    <input type="radio" name="jenis" value="io" class="mt-1 text-emerald-600 focus:ring-emerald-500" @checked($jenis === 'io')>
                    <span>
                        <span class="block text-sm font-semibold text-slate-800">Input / Output</span>
                        <span class="block text-xs text-slate-500 mt-0.5">Program dijalankan dengan input, lalu outputnya dibandingkan.</span>
                    </span>
                </label>
                <label class="flex items-start gap-3 rounded-xl border border-slate-200 p-3.5 cursor-pointer hover:bg-slate-50 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50">
                    <input type="radio" name="jenis" value="assertion" class="mt-1 text-emerald-600 focus:ring-emerald-500" @checked($jenis === 'assertion')>
                    <span>
                        <span class="block text-sm font-semibold text-slate-800">Assertion</span>
                        <span class="block text-xs text-slate-500 mt-0.5">Untuk soal berbasis fungsi, misalnya <code>assert jumlah(2, 3) == 5</code>.</span>
                    </span>
                </label>
            </div>
        </fieldset>

        {{-- Bagian Input / Output --}}
        <div id="bagian-io" class="space-y-5">
            <div>
                <label for="input" class="block text-xs font-semibold text-slate-700 mb-1">Input (opsional)</label>
                <textarea id="input" name="input" rows="3" spellcheck="false" class="{{ $inputClass }}">{{ old('input', $testCase->input ?? '') }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">Satu baris untuk tiap pemanggilan input(). Contoh dua bilangan: tulis 3, lalu 5 di baris berikutnya.</p>
            </div>
            <div>
                <label for="expected_output" class="block text-xs font-semibold text-slate-700 mb-1">Output yang diharapkan <span class="text-red-500">*</span></label>
                <textarea id="expected_output" name="expected_output" rows="3" spellcheck="false" class="{{ $inputClass }}">{{ old('expected_output', $testCase->expected_output ?? '') }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">Persis seperti yang dicetak program.</p>
            </div>
        </div>

        {{-- Bagian Assertion --}}
        <div id="bagian-assertion">
            <label for="assertion_code" class="block text-xs font-semibold text-slate-700 mb-1">Kode assertion <span class="text-red-500">*</span></label>
            <textarea id="assertion_code" name="assertion_code" rows="4" spellcheck="false" class="{{ $inputClass }}"
                      placeholder="assert jumlah(2, 3) == 5">{{ old('assertion_code', $testCase->assertion_code ?? '') }}</textarea>
            <p class="text-[11px] text-slate-400 mt-1">Dijalankan setelah kode siswa. Test case lolos bila semua assertion berhasil.</p>
        </div>

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
        <p class="text-[11px] text-slate-400">Test case yang tidak disembunyikan tampil sebagai "Contoh Uji Coba". Test case tersembunyi hanya dipakai saat penilaian akhir. Soal terbit wajib punya minimal satu test case terlihat.</p>

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

    @push('scripts')
        <script>
            (function () {
                var radios = document.querySelectorAll('input[name="jenis"]');
                var io = document.getElementById('bagian-io');
                var assertion = document.getElementById('bagian-assertion');

                function toggle() {
                    var selected = document.querySelector('input[name="jenis"]:checked');
                    var isAssertion = selected && selected.value === 'assertion';
                    io.classList.toggle('hidden', isAssertion);
                    assertion.classList.toggle('hidden', !isAssertion);
                }

                radios.forEach(function (radio) { radio.addEventListener('change', toggle); });
                toggle();
            })();
        </script>
    @endpush
</x-layouts.admin>