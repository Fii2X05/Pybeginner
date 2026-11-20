<x-layouts.admin :title="'Uji Kunci Jawaban - '.$exercise->title" active="latihan">
    <nav class="flex items-center gap-1.5 text-xs text-slate-400 mb-5">
        <a href="{{ route('admin.latihan') }}" class="hover:text-emerald-600 font-semibold">Latihan &amp; Soal</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{ route('admin.latihan.test-cases', $exercise) }}" class="hover:text-emerald-600 font-semibold">{{ $exercise->title }}</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-700 font-semibold">Uji Kunci Jawaban</span>
    </nav>

    <div class="mb-6">
        <h1 class="text-xl font-black text-slate-900">Uji Kunci Jawaban</h1>
        <p class="text-sm text-slate-500 mt-1">
            Jalankan solusi terhadap semua test case aktif, termasuk test case tersembunyi. Pengujian ini tidak membuat submission siswa.
        </p>
    </div>

    @if ($error)
        <div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            {{ $error }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.latihan.answer-key.run', $exercise) }}"
          class="max-w-4xl space-y-4 rounded-2xl border border-slate-200 bg-white p-6">
        @csrf
        <div>
            <label for="source_code" class="mb-1 block text-xs font-semibold text-slate-700">Kode solusi</label>
            <textarea id="source_code" name="source_code" rows="14" required maxlength="50000" spellcheck="false"
                      class="w-full rounded-xl border border-slate-200 bg-slate-950 p-4 font-mono text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ $sourceCode }}</textarea>
            @error('source_code')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex flex-wrap justify-end gap-2">
            <a href="{{ route('admin.latihan.test-cases', $exercise) }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Kembali ke Test Case
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-700">
                <i data-lucide="play" class="h-4 w-4"></i> Jalankan ke Semua Test Case
            </button>
        </div>
    </form>

    @if (is_array($results))
        @php
            $passedCount = collect($results)->where('passed', true)->count();
            $totalCount = count($results);
        @endphp
        <section class="mt-7 max-w-4xl" aria-live="polite">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-bold text-slate-900">Hasil Pengujian</h2>
                <span class="rounded-xl px-3 py-1.5 text-sm font-bold {{ $passedCount === $totalCount ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-800' }}">
                    {{ $passedCount }} dari {{ $totalCount }} test case lolos
                </span>
            </div>

            <div class="space-y-3">
                @foreach ($results as $index => $result)
                    <article class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                            <h3 class="font-semibold text-slate-900">
                                Test Case {{ $index + 1 }}
                                @if ($result['test_case']->is_hidden)
                                    <span class="ml-1 rounded-full bg-violet-50 px-2 py-0.5 text-xs font-semibold text-violet-700">Tersembunyi</span>
                                @endif
                            </h3>
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $result['passed'] ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                {{ $result['passed'] ? 'Lolos' : 'Gagal' }} · {{ $result['status'] }}
                            </span>
                        </div>

                        <div class="grid gap-3 text-sm sm:grid-cols-2">
                            @if ($result['is_assertion'])
                                <div class="sm:col-span-2">
                                    <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Assertion</p>
                                    <pre class="overflow-x-auto rounded-lg bg-slate-50 p-3 font-mono text-xs text-slate-700">{{ $result['test_case']->assertion_code }}</pre>
                                </div>
                            @else
                                <div>
                                    <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Input</p>
                                    <pre class="min-h-10 overflow-x-auto rounded-lg bg-slate-50 p-3 font-mono text-xs text-slate-700">{{ $result['test_case']->input ?? '(tanpa input)' }}</pre>
                                </div>
                                <div>
                                    <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Output yang diharapkan</p>
                                    <pre class="min-h-10 overflow-x-auto rounded-lg bg-slate-50 p-3 font-mono text-xs text-slate-700">{{ $result['test_case']->expected_output }}</pre>
                                </div>
                            @endif
                            <div class="sm:col-span-2">
                                <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Output aktual</p>
                                <pre class="overflow-x-auto rounded-lg bg-slate-50 p-3 font-mono text-xs text-slate-700">{{ $result['actual'] !== '' ? $result['actual'] : '(kosong)' }}</pre>
                            </div>
                            @if ($result['error'] !== '')
                                <div class="sm:col-span-2">
                                    <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-rose-600">Detail error</p>
                                    <pre class="overflow-x-auto rounded-lg bg-rose-50 p-3 font-mono text-xs text-rose-800">{{ $result['error'] }}</pre>
                                </div>
                            @endif
                        </div>
                        <p class="mt-3 text-right text-xs text-slate-400">Waktu eksekusi: {{ $result['time_ms'] }} ms</p>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.admin>