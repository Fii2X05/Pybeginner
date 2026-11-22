@php
    $isEditing = $testCase !== null;
    $action = $isEditing ? route('admin.test-cases.update', $testCase) : route('admin.latihan.test-cases.store', $exercise);
    $mode = old('mode', $testCase?->assertion_code ? 'assertion' : 'input_output');
    $inputClass = 'w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 font-mono text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100';
@endphp

<x-layouts.admin :title="($isEditing ? 'Edit' : 'Tambah') . ' Test Case - Admin'" active="latihan">
    <a href="{{ route('admin.latihan.test-cases', $exercise) }}" class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Test Case
    </a>
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-900">{{ $isEditing ? 'Edit' : 'Tambah' }} Test Case</h1>
        <p class="mt-1 text-sm text-slate-500">Latihan: {{ $exercise->title }}</p>
    </div>
    <form method="POST" action="{{ $action }}" class="max-w-3xl space-y-5 rounded-2xl border border-slate-100 bg-white p-5 shadow-xs sm:p-7">
        @csrf
        @if ($isEditing) @method('PUT') @endif
        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <div>
            <label for="mode" class="mb-1.5 block text-sm font-semibold text-slate-700">Metode evaluasi</label>
            <select id="mode" name="mode" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm">
                <option value="input_output" @selected($mode === 'input_output')>Input / Output</option>
                <option value="assertion" @selected($mode === 'assertion')>Assertion (uji fungsi)</option>
            </select>
            <p class="mt-1 text-xs text-slate-500">Assertion dijalankan setelah kode siswa dan cocok untuk soal yang meminta siswa membuat fungsi.</p>
        </div>

        <div id="input-output-fields" class="space-y-4">
            <div>
                <label for="input" class="mb-1.5 block text-sm font-semibold text-slate-700">Input (opsional)</label>
                <textarea id="input" name="input" rows="4" spellcheck="false" class="{{ $inputClass }}">{{ old('input', $testCase?->input) }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Satu baris untuk setiap pemanggilan input().</p>
            </div>
            <div>
                <label for="expected_output" class="mb-1.5 block text-sm font-semibold text-slate-700">Output yang diharapkan</label>
                <textarea id="expected_output" name="expected_output" rows="4" spellcheck="false" class="{{ $inputClass }}">{{ old('expected_output', $testCase?->expected_output) }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Dibandingkan dengan output program setelah spasi tepi dinormalisasi.</p>
            </div>
        </div>
        <div id="assertion-fields" class="hidden">
            <label for="assertion_code" class="mb-1.5 block text-sm font-semibold text-slate-700">Kode assertion</label>
            <textarea id="assertion_code" name="assertion_code" rows="7" spellcheck="false" placeholder="assert jumlah(2, 3) == 5" class="{{ $inputClass }}">{{ old('assertion_code', $testCase?->assertion_code) }}</textarea>
            <p class="mt-1 text-xs text-slate-500">Contoh: <code>assert jumlah(2, 3) == 5</code>. Jika metode assertion dipilih, input/output tidak dipakai.</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label for="weight" class="mb-1.5 block text-sm font-semibold text-slate-700">Bobot (1-100)</label>
                <input id="weight" name="weight" type="number" min="1" max="100" required value="{{ old('weight', $testCase?->weight ?? 1) }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm">
            </div>
            <div>
                <label for="sort_order" class="mb-1.5 block text-sm font-semibold text-slate-700">Urutan</label>
                <input id="sort_order" name="sort_order" type="number" min="1" value="{{ old('sort_order', $testCase?->sort_order) }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm">
            </div>
            <label class="flex items-end gap-2 pb-2 text-sm font-semibold text-slate-700">
                <input type="hidden" name="is_hidden" value="0">
                <input type="checkbox" name="is_hidden" value="1" @checked(old('is_hidden', $testCase?->is_hidden ?? false)) class="rounded border-slate-300 text-emerald-600">
                Sembunyikan dari siswa
            </label>
        </div>
        <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
            <a href="{{ route('admin.latihan.test-cases', $exercise) }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600">Batal</a>
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">Simpan Test Case</button>
        </div>
    </form>

    @push('scripts')
    <script>
        (() => {
            const mode = document.getElementById('mode');
            const inputOutput = document.getElementById('input-output-fields');
            const assertion = document.getElementById('assertion-fields');
            const input = document.getElementById('input');
            const output = document.getElementById('expected_output');
            const assertionCode = document.getElementById('assertion_code');
            const updateMode = () => {
                const usesAssertion = mode.value === 'assertion';
                inputOutput.classList.toggle('hidden', usesAssertion);
                assertion.classList.toggle('hidden', !usesAssertion);
                input.disabled = usesAssertion;
                output.disabled = usesAssertion;
                output.required = !usesAssertion;
                assertionCode.disabled = !usesAssertion;
                assertionCode.required = usesAssertion;
            };
            mode.addEventListener('change', updateMode);
            updateMode();
        })();
    </script>
    @endpush
</x-layouts.admin>
