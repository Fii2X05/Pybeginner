<x-layouts.app :title="$exercise->title" active="latihan">

    @php
        $levelLabel = $levels[$exercise->difficulty]['label'] ?? $exercise->difficulty;
        $levelChip  = [
            'easy'   => 'bg-emerald-100 text-emerald-800',
            'medium' => 'bg-amber-100 text-amber-800',
            'hard'   => 'bg-rose-100 text-rose-800',
        ][$exercise->difficulty] ?? 'bg-emerald-100 text-emerald-800';
        $tabOn  = 'border-emerald-600 text-emerald-700';
        $tabOff = 'border-transparent text-slate-500 hover:text-slate-800';
        $steps  = [
            ['Baca soal',       'Pahami tantangan di kiri'],
            ['Tulis kode',      'Isi di editor'],
            ['Jalankan & uji',  'Coba, lalu uji test case'],
            ['Kirim jawaban',   'Dapatkan nilaimu'],
        ];
    @endphp

    {{-- BREADCRUMB --}}
    <div class="mb-4 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm">
        <a href="{{ route('latihan.index', ['level' => $exercise->difficulty]) }}"
           class="inline-flex items-center gap-1.5 font-medium text-emerald-700 hover:underline">&larr; Kembali ke daftar latihan</a>
        <span class="hidden text-slate-300 sm:inline">/</span>
        <span class="hidden text-slate-500 sm:inline">{{ $levelLabel }}</span>
    </div>

    <div class="mb-5 flex flex-wrap items-center gap-2 sm:gap-3">
        <h1 class="text-2xl font-extrabold text-slate-900 sm:text-3xl">{{ $exercise->title }}</h1>
        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $levelChip }}">{{ $levelLabel }}</span>
    </div>

    {{-- PANDUAN LANGKAH --}}
    <section class="mb-6 rounded-3xl bg-white p-4 shadow-sm ring-1 ring-emerald-100 sm:p-5" aria-label="Panduan langkah">
        <ol id="stepper" class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            @foreach ($steps as $n => $st)
                <li data-step="{{ $n + 1 }}" class="flex items-center gap-3">
                    <span data-dot class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-200 text-sm font-bold text-slate-500">{{ $n + 1 }}</span>
                    <span class="min-w-0">
                        <span data-label class="block text-sm font-medium text-slate-400">{{ $st[0] }}</span>
                        <span class="hidden truncate text-[11px] text-slate-400 sm:block">{{ $st[1] }}</span>
                    </span>
                </li>
            @endforeach
        </ol>

        <div id="guide-hint" class="mt-4 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8h.01M11 12h1v4h1"/></svg>
            <div class="min-w-0">
                <p id="guide-title" class="text-[11px] font-bold uppercase tracking-widest text-emerald-700">Langkah 1 dari 4</p>
                <p id="guide-text" class="mt-0.5 text-sm leading-relaxed text-slate-700">Baca deskripsi dan contoh test case di panel kiri, lalu klik editor untuk mulai menulis.</p>
            </div>
        </div>
    </section>

    <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">

        {{-- KIRI: SOAL --}}
        <section class="min-w-0 overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-emerald-100">
            <div class="flex gap-5 border-b border-emerald-100 px-5 text-sm font-semibold sm:px-7" role="tablist">
                <button type="button" role="tab" data-tab-group="left" data-tab-btn="deskripsi"
                        class="-mb-px border-b-2 py-3.5 transition {{ $tabOn }}">Deskripsi Soal</button>
                @if ($lesson)
                    <button type="button" role="tab" data-tab-group="left" data-tab-btn="materi"
                            class="-mb-px border-b-2 py-3.5 transition {{ $tabOff }}">Petunjuk &amp; Teori</button>
                @endif
            </div>

            {{-- Pane: Deskripsi --}}
            <div data-tab-group="left" data-tab-pane="deskripsi" class="p-5 sm:p-7">
                <h2 class="text-xs font-bold uppercase tracking-widest text-emerald-600">Deskripsi Tantangan</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-700 sm:text-base">{{ $exercise->description }}</p>

                @if ($exercise->instructions)
                    <h2 class="mt-6 text-xs font-bold uppercase tracking-widest text-emerald-600">Instruksi Pengerjaan</h2>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-700 sm:text-base">{{ $exercise->instructions }}</p>
                @endif

                <h2 class="mt-6 text-xs font-bold uppercase tracking-widest text-emerald-600">Contoh Uji Coba</h2>
                <p class="mt-1 text-xs text-slate-500">Kodemu harus menghasilkan <em>output</em> yang sama persis untuk <em>input</em> di bawah ini.</p>
                <div class="mt-3 space-y-3">
                    @foreach ($samples as $i => $s)
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-4 text-xs text-slate-700">
                            <p class="mb-2 text-xs font-semibold text-emerald-700">Contoh {{ $i + 1 }}</p>
                            @if ($s->assertion_code)
                                <pre class="overflow-x-auto whitespace-pre-wrap rounded-lg bg-white p-3 font-mono ring-1 ring-emerald-100">{{ $s->assertion_code }}</pre>
                            @else
                                <div class="grid gap-2 sm:grid-cols-2">
                                    <div class="min-w-0 rounded-lg bg-white p-3 ring-1 ring-emerald-100">
                                        <p class="mb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Input</p>
                                        <p class="whitespace-pre-line break-words font-mono text-slate-800">{{ $s->input }}</p>
                                    </div>
                                    <div class="min-w-0 rounded-lg bg-white p-3 ring-1 ring-emerald-100">
                                        <p class="mb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Output yang diharapkan</p>
                                        <p class="whitespace-pre-line break-words font-mono text-emerald-700">{{ $s->expected_output }}</p>
                                    </div>
                                </div>
                            @endif
                            @auth
                                <div class="mt-3 flex flex-col gap-2.5 border-t border-emerald-100 pt-3 sm:flex-row sm:items-center sm:justify-between">
                                    <span class="test-case-progress text-[11px] text-slate-500" data-test-case-progress="{{ $s->id }}">Belum diuji</span>
                                    <button type="button"
                                            class="btn-test-case w-full rounded-lg border border-emerald-300 bg-white px-3 py-1.5 text-center text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50 disabled:opacity-50 sm:w-auto"
                                            data-test-case-id="{{ $s->id }}"
                                            data-test-case-url="{{ route('submissions.test-case.run', ['exerciseId' => $exercise->id, 'testCaseId' => $s->id]) }}">
                                        Uji test case ini
                                    </button>
                                </div>
                            @endauth
                        </div>
                    @endforeach
                </div>
                @auth
                    <p id="test-case-summary" class="mt-3 text-xs leading-relaxed text-slate-500">Status test case memakai input acuan yang ditampilkan di kartu ini. Input Program hanya dipakai oleh Jalankan Kode dan tidak mengubah status test case.</p>
                @endauth
            </div>

            {{-- Pane: Materi (terbuka otomatis kalau jawaban belum benar) --}}
            @if ($lesson)
                <div id="materi" data-tab-group="left" data-tab-pane="materi" class="hidden p-5 sm:p-7">
                    <p class="text-xs font-bold uppercase tracking-widest text-yellow-600">Baca dulu materinya</p>
                    <h2 class="mt-1 text-lg font-bold text-slate-900">{{ $lesson->title }}</h2>
                    <div class="mt-3 text-sm leading-relaxed text-slate-700">
                        {!! preg_replace('~(https?://[^\s<]+)~', '<a href="$1" target="_blank" rel="noopener" class="break-all text-emerald-700 underline">$1</a>', nl2br(e($lesson->content))) !!}
                    </div>
                    <button id="btn-ulang" type="button"
                            class="mt-5 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500">
                        &larr; Sudah paham, coba lagi
                    </button>
                </div>
            @endif
        </section>

        {{-- KANAN: EDITOR + AKSI + HASIL --}}
        <section class="min-w-0 space-y-5">

            {{-- Langkah 2: editor (satu-satunya bagian gelap) --}}
            <div id="editor-wrap" class="overflow-hidden rounded-3xl bg-slate-900 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-2 px-4 py-3 text-xs text-slate-300 sm:px-5">
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-yellow-400 px-2 py-0.5 text-[10px] font-bold text-slate-900">Langkah 2</span>
                        <span class="font-semibold">Tulis kodemu di sini</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-mono text-slate-400">solution.py &middot; Python 3</span>
                        <button id="btn-reset" type="button" class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-slate-400 transition hover:bg-slate-800 hover:text-slate-100"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Reset</button>
                    </div>
                </div>
                <div id="editor" style="height: 340px;"></div>
                <div class="flex items-center gap-4 border-t border-slate-800 px-4 py-1.5 font-mono text-[11px] text-slate-500 sm:px-5">
                    <span id="cursor-pos">Ln 1, Col 1</span>
                    <span>Spaces: 4</span>
                    <span>UTF-8</span>
                </div>
            </div>

            {{-- Langkah 3 & 4: aksi --}}
            <div class="rounded-3xl bg-white p-4 shadow-sm ring-1 ring-emerald-100 sm:p-5">
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="rounded-full bg-yellow-400 px-2 py-0.5 text-[10px] font-bold text-slate-900">Langkah 3</span>
                    <span class="font-semibold text-slate-700">Jalankan &amp; uji kodemu</span>
                    <span class="text-slate-300">&rarr;</span>
                    <span class="rounded-full bg-yellow-400 px-2 py-0.5 text-[10px] font-bold text-slate-900">Langkah 4</span>
                    <span class="font-semibold text-slate-700">Kirim jawaban</span>
                </div>

                <div class="mt-3 grid gap-2.5 sm:grid-cols-3">
                    <button id="btn-run" type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-500 px-4 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50 disabled:cursor-not-allowed disabled:opacity-50">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg> Jalankan Kode
                    </button>
                    @auth
                        <button id="btn-test-all" type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-500 px-4 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50 disabled:cursor-not-allowed disabled:opacity-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg> Uji Semua Test Case
                        </button>
                        <button id="btn-submit" type="button" disabled
                                class="rounded-xl bg-yellow-400 px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-yellow-300 disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none">
                            Kirim &amp; Nilai Jawaban
                        </button>
                    @else
                        <a href="{{ route('login') }}"
                           class="rounded-xl bg-yellow-400 px-4 py-2.5 text-center text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-yellow-300 sm:col-span-2">
                            Masuk untuk Menguji &amp; Mengirim
                        </a>
                    @endauth
                </div>

                @auth
                    <p id="submit-hint" class="mt-2.5 text-xs text-slate-500">Tombol Kirim aktif setelah semua test case contoh Lolos.</p>
                @endauth

                <label class="mt-4 inline-flex cursor-pointer items-center gap-2 text-xs text-slate-600">
                    <input id="use-stdin" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    Soal minta input? Isi input manual (STDIN) untuk &ldquo;Jalankan Kode&rdquo;
                </label>
                <div id="stdin-wrap" class="mt-3 hidden">
                    <p class="mb-2 text-[11px] text-slate-500">Hanya dipakai oleh Jalankan Kode. Penilaian test case memakai input acuan di kartu sebelah kiri.</p>
                    <textarea id="custom-stdin" rows="3" placeholder="Masukkan input : "
                              class="w-full resize-y rounded-xl border border-emerald-100 bg-slate-50 p-3 font-mono text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-100"></textarea>
                </div>
            </div>

            {{-- Hasil --}}
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-emerald-100">
                <div class="flex gap-5 border-b border-emerald-100 px-4 text-sm font-semibold sm:px-5" role="tablist">
                    <button type="button" role="tab" data-tab-group="out" data-tab-btn="terminal" class="-mb-px border-b-2 py-3 transition {{ $tabOn }}">Hasil Program</button>
                    <button type="button" role="tab" data-tab-group="out" data-tab-btn="tests"    class="-mb-px border-b-2 py-3 transition {{ $tabOff }}">Hasil Test Case</button>
                    <button type="button" role="tab" data-tab-group="out" data-tab-btn="error"    class="-mb-px inline-flex items-center gap-1.5 border-b-2 py-3 transition {{ $tabOff }}">
                        Pesan Error <span id="err-dot" class="hidden h-1.5 w-1.5 rounded-full bg-red-500"></span>
                    </button>
                </div>
                <div class="min-h-[150px] min-w-0 overflow-hidden px-4 py-4 text-sm sm:px-5">
                    <div id="out-terminal" data-tab-group="out" data-tab-pane="terminal"></div>
                    <div id="out-tests"    data-tab-group="out" data-tab-pane="tests"    class="hidden"></div>
                    <div id="out-error"    data-tab-group="out" data-tab-pane="error"    class="hidden"></div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>
    <script>
        const EXERCISE_ID = {{ $exercise->id }};
        const STARTER = @json($exercise->starter_code ?? '');
        const URL_LIST = @json(route('latihan.index', ['level' => $exercise->difficulty]));
        const RUN_URL = @json(route('submissions.execute'));
        const SUBMIT_URL = @json(route('submissions.submit'));
        const TEST_CASE_PROGRESS_URL = @json(route('submissions.test-case.progress', ['exerciseId' => $exercise->id]));
        const IS_AUTH = @json(auth()->check());
        let editor;
        const testCaseProgress = new Map();
        const testDetail = new Map();

        // status alur
        let readDone = false, hasRun = false, submittedPerfect = false, lastSubmitFailed = false;
        let tcPassed = 0, tcTotal = 0;

        const TAB_ON  = ['border-emerald-600', 'text-emerald-700'];
        const TAB_OFF = ['border-transparent', 'text-slate-500', 'hover:text-slate-800'];

        // ---------- TAB ----------
        function activateTab(group, name) {
            document.querySelectorAll(`[data-tab-group="${group}"][data-tab-btn]`).forEach((btn) => {
                const on = btn.dataset.tabBtn === name;
                TAB_ON.forEach((c) => btn.classList.toggle(c, on));
                TAB_OFF.forEach((c) => btn.classList.toggle(c, !on));
                btn.setAttribute('aria-selected', on ? 'true' : 'false');
            });
            document.querySelectorAll(`[data-tab-group="${group}"][data-tab-pane]`).forEach((pane) => {
                pane.classList.toggle('hidden', pane.dataset.tabPane !== name);
            });
        }
        document.querySelectorAll('[data-tab-btn]').forEach((btn) => {
            btn.addEventListener('click', () => activateTab(btn.dataset.tabGroup, btn.dataset.tabBtn));
        });

        // ---------- PANDUAN LANGKAH ----------
        const CHECK_ICON = '<svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>';
        const DOT_BASE  = 'flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-bold transition';
        const DOT_DONE  = 'bg-emerald-600 text-white';
        const DOT_NOW   = 'bg-yellow-400 text-slate-900 ring-4 ring-yellow-200';
        const DOT_NEXT  = 'bg-slate-200 text-slate-500';
        const LBL_BASE  = 'block text-sm transition';
        const LBL_DONE  = 'font-semibold text-emerald-700';
        const LBL_NOW   = 'font-bold text-slate-900';
        const LBL_NEXT  = 'font-medium text-slate-400';

        function updateGuide() {
            const changed = !!editor && editor.getValue().trim() !== STARTER.trim();
            const canSubmit = IS_AUTH && tcPassed >= tcTotal;

            let step;
            if (submittedPerfect) step = 5;
            else if (changed && canSubmit) step = 4;
            else if (changed) step = 3;
            else if (readDone) step = 2;
            else step = 1;

            document.querySelectorAll('#stepper [data-step]').forEach((li) => {
                const n = Number(li.dataset.step);
                const state = n < step ? 'done' : (n === step ? 'now' : 'next');
                const dot = li.querySelector('[data-dot]');
                const label = li.querySelector('[data-label]');
                dot.className = `${DOT_BASE} ${state === 'done' ? DOT_DONE : state === 'now' ? DOT_NOW : DOT_NEXT}`;
                if (state === 'done') { dot.innerHTML = CHECK_ICON; } else { dot.textContent = String(n); }
                label.className = `${LBL_BASE} ${state === 'done' ? LBL_DONE : state === 'now' ? LBL_NOW : LBL_NEXT}`;
            });

            let title, text;
            if (step === 1) {
                title = 'Langkah 1 dari 4';
                text = 'Baca deskripsi dan contoh test case di panel kiri, lalu klik editor untuk mulai menulis.';
            } else if (step === 2) {
                title = 'Langkah 2 dari 4';
                text = 'Tulis kodemu di editor, di bawah komentar "# tulis kodemu di bawah ini".';
            } else if (step === 3) {
                title = 'Langkah 3 dari 4';
                if (lastSubmitFailed) {
                    text = 'Belum tepat. Buka tab "Petunjuk & Teori" di kiri, perbaiki kodemu, lalu uji lagi.';
                } else if (!IS_AUTH) {
                    text = 'Klik "Jalankan Kode" untuk melihat hasilnya. Untuk menguji test case dan mengirim jawaban, masuk dulu.';
                } else if (!hasRun && tcPassed === 0) {
                    text = 'Klik "Jalankan Kode" untuk mencoba, lalu "Uji Semua Test Case" sampai semuanya Lolos.';
                } else {
                    text = `Klik "Uji Semua Test Case" sampai semuanya Lolos (${tcPassed} dari ${tcTotal} lolos sekarang).`;
                }
            } else if (step === 4) {
                title = 'Langkah 4 dari 4';
                text = 'Semua test case contoh Lolos! Klik "Kirim & Nilai Jawaban" untuk mendapat nilaimu.';
            } else {
                title = 'Selesai';
                text = 'Jawabanmu benar semua. Lanjut ke soal berikutnya dari daftar latihan.';
            }
            document.getElementById('guide-title').textContent = title;
            document.getElementById('guide-text').textContent = text;

            const hint = document.getElementById('submit-hint');
            if (hint) {
                hint.textContent = canSubmit && changed
                    ? 'Siap! Semua test case contoh lolos, kamu bisa mengirim jawaban.'
                    : `Tombol Kirim aktif setelah semua test case contoh Lolos (${tcPassed} dari ${tcTotal} lolos). Klik "Uji Semua Test Case".`;
            }
        }

        // ---------- EDITOR ----------
        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            monaco.editor.defineTheme('pyb-dark', {
                base: 'vs-dark', inherit: true, rules: [],
                colors: {
                    'editor.background': '#0f172a',
                    'editorGutter.background': '#0f172a',
                    'editor.lineHighlightBackground': '#162033',
                },
            });
            editor = monaco.editor.create(document.getElementById('editor'), {
                value: STARTER, language: 'python', theme: 'pyb-dark',
                fontSize: 14, minimap: { enabled: false }, automaticLayout: true,
                tabSize: 4, scrollBeyondLastLine: false, padding: { top: 12 },
            });
            editor.onDidFocusEditorText(() => { readDone = true; updateGuide(); });
            editor.onDidChangeModelContent(() => {
                readDone = true; submittedPerfect = false; lastSubmitFailed = false;
                renderTestCaseProgress();
            });
            editor.onDidChangeCursorPosition((e) => {
                document.getElementById('cursor-pos').textContent = `Ln ${e.position.lineNumber}, Col ${e.position.column}`;
            });
            loadTestCaseProgress();
            updateGuide();
        });

        // ---------- OUTPUT ----------
        const outTerminal = document.getElementById('out-terminal');
        const outTests    = document.getElementById('out-tests');
        const outError    = document.getElementById('out-error');
        const errDot      = document.getElementById('err-dot');
        const materiEl    = document.getElementById('materi'); // null kalau tidak ada materi

        const P = (t) => `<p class="text-sm text-slate-500">${t}</p>`;
        const WARN = (t) => `<p class="text-sm text-amber-700">${t}</p>`;
        const PH_TERMINAL = P('Klik <strong>Jalankan Kode</strong> untuk melihat keluaran programmu di sini.');
        const PH_TESTS    = P('Klik <strong>Uji Semua Test Case</strong> untuk memeriksa kodemu terhadap contoh soal.');
        const PH_ERROR    = P('Belum ada error. Bagus!');

        function resetOutputs() {
            outTerminal.innerHTML = PH_TERMINAL;
            outTests.innerHTML = PH_TESTS;
            outError.innerHTML = PH_ERROR;
            errDot.classList.add('hidden');
        }
        resetOutputs();

        function esc(t) {
            return String(t ?? '').replace(/[&<>]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[c]));
        }

        function setError(text) {
            if (text) {
                outError.innerHTML = `<pre class="overflow-x-auto whitespace-pre-wrap rounded-xl border border-red-200 bg-red-50 p-3 font-mono text-xs text-red-800">${esc(text)}</pre>
                    <p class="mt-2 text-xs text-slate-500">Tips: baca baris terakhir pesan error, biasanya menunjukkan jenis kesalahan dan nomor barisnya.</p>`;
                errDot.classList.remove('hidden');
            } else {
                outError.innerHTML = PH_ERROR;
                errDot.classList.add('hidden');
            }
        }

        // ---------- PROGRES TEST CASE ----------
        function renderTestCaseProgress(progress = null) {
            const currentCode = editor?.getValue() ?? '';
            if (progress) {
                testCaseProgress.clear();
                Object.entries(progress).forEach(([testCaseId, status]) => {
                    testCaseProgress.set(String(testCaseId), { code: currentCode, status });
                });
            }

            document.querySelectorAll('[data-test-case-progress]').forEach((element) => {
                const state = testCaseProgress.get(element.dataset.testCaseProgress);
                const isCurrentCode = state?.code === currentCode;
                const status = isCurrentCode ? state.status : 'Belum diuji';
                element.textContent = status === 'passed' ? 'Lolos' : status === 'failed' ? 'Belum lolos' : 'Belum diuji';
                element.className = `test-case-progress text-[11px] ${status === 'passed' ? 'font-semibold text-emerald-700' : status === 'failed' ? 'font-semibold text-red-600' : 'text-slate-500'}`;
            });

            const total = document.querySelectorAll('[data-test-case-progress]').length;
            const passed = [...testCaseProgress.entries()].filter(([testCaseId, state]) =>
                state.code === currentCode && state.status === 'passed'
            ).length;
            tcTotal = total;
            tcPassed = passed;

            const summary = document.getElementById('test-case-summary');
            if (summary) {
                summary.textContent = `${passed}/${total} test case terlihat lolos untuk kode saat ini. Status memakai input acuan di kartu; Input Program hanya untuk Jalankan Kode dan tidak mengubah status. Test case tersembunyi diperiksa saat pengiriman akhir.`;
            }
            const submitButton = document.getElementById('btn-submit');
            if (submitButton) {
                submitButton.disabled = passed < total;
            }
            updateGuide();
        }

        async function loadTestCaseProgress() {
            if (!editor || !document.getElementById('test-case-summary')) return;

            try {
                const res = await fetch(TEST_CASE_PROGRESS_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ source_code: editor.getValue() }),
                });
                const data = await res.json();
                if (res.ok && data.success) renderTestCaseProgress(data.progress);
            } catch (e) {
                const summary = document.getElementById('test-case-summary');
                if (summary) summary.textContent = 'Progress test case tidak dapat dimuat. Coba muat ulang halaman.';
            }
        }

        // ---------- UJI TEST CASE ----------
        function kartuTes(data, judul) {
            const style = data.is_passed
                ? { card: 'border-emerald-200 bg-emerald-50', title: 'text-emerald-800' }
                : { card: 'border-red-200 bg-red-50', title: 'text-red-700' };
            return `<div class="rounded-xl border p-4 text-sm ${style.card}">
                <p class="font-semibold ${style.title}">${judul}: ${data.is_passed ? 'Lolos' : 'Belum lolos'}</p>
                <p class="mt-2 whitespace-pre-wrap break-words font-mono text-xs text-slate-700">Input: ${esc(data.input)}\nDiharapkan: ${esc(data.expected)}\nKeluaran: ${esc(data.actual)}</p>
                ${!data.is_passed ? '<p class="mt-2 text-xs text-slate-500">Bandingkan "Diharapkan" dengan "Keluaran". Cek spasi, huruf besar/kecil, dan tanda baca.</p>' : ''}
            </div>`;
        }

        async function jalankanTestCase(button, quiet = false) {
            activateTab('out', 'tests');
            if (!editor) {
                outTests.innerHTML = WARN('Editor belum siap. Muat ulang halaman dan coba lagi.');
                return;
            }
            hasRun = true;

            const testCaseId = button.dataset.testCaseId;
            const sourceCode = editor.getValue();
            const progressLabel = document.querySelector(`[data-test-case-progress="${testCaseId}"]`);
            button.disabled = true;
            button.textContent = 'Menguji...';
            if (progressLabel) progressLabel.textContent = 'Sedang diuji...';
            if (!quiet) outTests.innerHTML = P('Menguji kode pada test case terpilih...');

            try {
                const res = await fetch(button.dataset.testCaseUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ source_code: sourceCode }),
                });
                const data = await res.json();
                if (!res.ok || !data.success) {
                    outTests.innerHTML = WARN(esc(data.message || 'Test case tidak dapat dijalankan.'));
                    return;
                }

                testCaseProgress.set(String(testCaseId), {
                    code: sourceCode,
                    status: data.is_passed ? 'passed' : 'failed',
                });
                testDetail.set(String(testCaseId), data);
                renderTestCaseProgress();
                if (!quiet) {
                    outTests.innerHTML = kartuTes(data, 'Test case')
                        + `<p class="mt-2 text-xs text-slate-500">${data.passed_tests}/${data.total_tests} test case terlihat lolos.</p>`;
                }
                if (data.error) setError(data.error);
            } catch (e) {
                outTests.innerHTML = WARN('Gagal terhubung ke server.');
            } finally {
                button.disabled = false;
                button.textContent = 'Uji test case ini';
                renderTestCaseProgress();
            }
        }

        async function ujiSemua() {
            const buttons = [...document.querySelectorAll('.btn-test-case')];
            const allBtn = document.getElementById('btn-test-all');
            if (!editor || !buttons.length || !allBtn) return;

            const originalText = allBtn.innerHTML;
            allBtn.disabled = true;
            allBtn.textContent = 'Menguji...';
            activateTab('out', 'tests');
            outTests.innerHTML = P('Menguji semua test case...');
            testDetail.clear();

            for (const b of buttons) {
                await jalankanTestCase(b, true);
            }

            let html = `<p class="text-sm font-bold ${tcPassed >= tcTotal ? 'text-emerald-700' : 'text-slate-800'}">${tcPassed} dari ${tcTotal} test case lolos</p>
                <div class="mt-3 space-y-2">`;
            buttons.forEach((b, i) => {
                const d = testDetail.get(String(b.dataset.testCaseId));
                if (d) html += kartuTes(d, `Contoh ${i + 1}`);
            });
            outTests.innerHTML = html + '</div>';

            allBtn.disabled = false;
            allBtn.innerHTML = originalText;
            updateGuide();
        }

        // ---------- HASIL KIRIM & NILAI ----------
        function tampilkanHasil(data, isSubmit) {
            const sempurna = Number(data.score) >= 100;
            const persen = Math.max(0, Math.min(100, Number(data.score) || 0));
            const errors = [];

            let html = `<div class="flex items-end justify-between">
                    <p class="text-2xl font-extrabold text-slate-900">${data.score}<span class="text-sm font-medium text-slate-400"> / 100</span></p>
                    <p class="text-sm text-slate-500">${data.passed_tests}/${data.total_tests} test case lolos</p>
                </div>
                <div class="mt-2 h-2 overflow-hidden rounded-full bg-emerald-100">
                    <div class="h-full rounded-full bg-emerald-500" style="width:${persen}%"></div>
                </div>`;

            if (isSubmit) {
                html += sempurna
                    ? `<div class="mt-4 rounded-2xl bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
                            Mantap! Semua test case lolos.
                            <a href="${URL_LIST}" class="ml-1 underline">Lanjut ke soal berikutnya &rarr;</a>
                       </div>`
                    : `<div class="mt-4 rounded-2xl bg-yellow-50 p-4 text-sm font-semibold text-yellow-800">
                            Belum tepat. Baca tab "Petunjuk &amp; Teori" di kiri dulu, lalu coba lagi.
                       </div>`;
            }

            if (data.user_stdout && String(data.user_stdout).trim() !== '') {
                html += `<div class="mt-4">
                    <p class="mb-1 text-xs font-semibold text-slate-500">Output Program (print)</p>
                    <pre class="overflow-x-auto whitespace-pre-wrap rounded-xl bg-slate-900 p-3 font-mono text-xs text-slate-100">${esc(data.user_stdout)}</pre>
                </div>`;
            }

            html += `<div class="mt-4 space-y-2">`;
            data.results.forEach((r, i) => {
                const warna = r.is_passed ? 'border-emerald-200 bg-emerald-50' : 'border-yellow-300 bg-yellow-50';
                html += `<div class="rounded-xl border p-3 text-sm ${warna}">
                    <p class="font-semibold text-slate-800">Test Case ${i + 1}${r.is_hidden ? ' (tersembunyi)' : ''}: ${r.is_passed ? 'Lolos' : 'Gagal'}</p>`;
                if (!r.is_hidden) {
                    html += `<p class="mt-1 break-words font-mono text-xs text-slate-600">Input: ${esc(r.input)}<br>Diharapkan: ${esc(r.expected)}<br>Keluaran: ${esc(r.actual)}</p>`;
                }
                if (r.error) {
                    html += `<p class="mt-1 whitespace-pre-wrap font-mono text-xs text-yellow-800">${esc(r.error)}</p>`;
                    errors.push(`Test Case ${i + 1}: ${r.error}`);
                }
                html += `</div>`;
            });
            outTests.innerHTML = html + '</div>';
            if (data.error_message && errors.length === 0) {
                errors.push(String(data.error_message));
            }
            setError(errors.join('\n\n'));
            activateTab('out', 'tests');

            if (isSubmit) {
                submittedPerfect = sempurna;
                lastSubmitFailed = !sempurna;
                updateGuide();
            }

            // materi hanya muncul setelah KIRIM dan jawaban belum sempurna
            if (materiEl && isSubmit && !sempurna) {
                activateTab('left', 'materi');
                materiEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        // ---------- HASIL JALANKAN KODE ----------
        function tampilkanHasilEksekusi(data, stdin) {
            const status = data.status?.description || 'Status tidak diketahui';
            const berhasil = Number(data.status?.id) === 3;
            const keluaran = data.stdout || '';
            const error = [data.compile_output, data.stderr, data.message].filter(Boolean).join('\n');

            outTerminal.innerHTML = `<div class="flex items-center justify-between">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Hasil Eksekusi</h2>
                    <span class="rounded-lg px-2.5 py-1 text-xs font-bold ${berhasil ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'}">${esc(status)}</span>
                </div>
                <p class="mt-2 text-xs text-slate-500">Input manual: <span class="font-mono text-slate-700">${esc(stdin || '(kosong)')}</span>. Hasil ini terpisah dari test case. Waktu: ${esc(data.time ?? '-')}s</p>
                <pre class="mt-3 overflow-x-auto whitespace-pre-wrap rounded-xl bg-slate-900 p-3 font-mono text-sm text-slate-100"><span class="text-slate-500">$ python3 solution.py</span>\n${esc(keluaran || '(tidak ada output)')}</pre>`;
            setError(error);
            activateTab('out', error && !berhasil ? 'error' : 'terminal');
        }

        async function jalankanKode() {
            activateTab('out', 'terminal');
            outTerminal.innerHTML = P('Menjalankan kode...');

            if (!editor) {
                outTerminal.innerHTML = WARN('Editor belum siap. Muat ulang halaman dan coba lagi.');
                return;
            }
            hasRun = true;
            updateGuide();

            const useStdin = document.getElementById('use-stdin')?.checked;
            const stdin = useStdin ? (document.getElementById('custom-stdin')?.value ?? '') : '';
            try {
                const res = await fetch(RUN_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        exercise_id: EXERCISE_ID,
                        source_code: editor.getValue(),
                        stdin,
                    }),
                });
                const data = await res.json();
                if (!res.ok || !data.success) {
                    outTerminal.innerHTML = WARN(esc(data.message || 'Eksekusi kode gagal.'));
                    return;
                }
                tampilkanHasilEksekusi(data, stdin);
            } catch (e) {
                outTerminal.innerHTML = WARN('Gagal terhubung ke server.');
            }
        }

        // ---------- KIRIM & NILAI ----------
        let isProcessing = false;

        async function kirim(url, isSubmit) {
            if (isProcessing) return;

            activateTab('out', 'tests');
            if (!editor) {
                outTests.innerHTML = WARN('Editor belum siap. Muat ulang halaman dan coba lagi.');
                return;
            }

            isProcessing = true;
            const btnRun = document.getElementById('btn-run');
            const submitBtn = document.getElementById('btn-submit');
            const originalRunText = btnRun ? btnRun.innerHTML : '';
            const originalSubmitText = submitBtn ? submitBtn.innerHTML : '';

            if (btnRun) btnRun.disabled = true;
            if (submitBtn) {
                submitBtn.disabled = true;
                if (isSubmit) submitBtn.innerText = 'Menilai...';
            }
            if (!isSubmit && btnRun) {
                btnRun.innerText = 'Menjalankan...';
            }

            outTests.innerHTML = P('Memproses...');

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ source_code: editor.getValue(), exercise_id: EXERCISE_ID }),
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    outTests.innerHTML = WARN(esc(data.message || 'Permintaan tidak dapat diproses.'));
                    return;
                }

                tampilkanHasil(data, isSubmit);
            } catch (e) {
                outTests.innerHTML = WARN('Gagal terhubung ke server.');
            } finally {
                isProcessing = false;
                if (btnRun) {
                    btnRun.disabled = false;
                    btnRun.innerHTML = originalRunText;
                }
                if (submitBtn) {
                    submitBtn.innerHTML = originalSubmitText;
                }
                renderTestCaseProgress();
            }
        }

        // ---------- EVENT ----------
        document.getElementById('btn-run').addEventListener('click', jalankanKode);
        document.querySelectorAll('.btn-test-case').forEach((button) => {
            button.addEventListener('click', () => jalankanTestCase(button));
        });
        const testAllButton = document.getElementById('btn-test-all');
        if (testAllButton) testAllButton.addEventListener('click', ujiSemua);

        const submitButton = document.getElementById('btn-submit');
        if (submitButton) {
            submitButton.addEventListener('click', () => kirim(SUBMIT_URL, true));
        }

        document.getElementById('use-stdin').addEventListener('change', function () {
            document.getElementById('stdin-wrap').classList.toggle('hidden', !this.checked);
            if (this.checked) document.getElementById('custom-stdin').focus();
        });

        document.getElementById('btn-reset').addEventListener('click', () => {
            if (!editor) return;
            if (confirm('Kembalikan kode ke awal? Perubahanmu di editor akan hilang.')) {
                editor.setValue(STARTER);
                editor.focus();
            }
        });

        // "Coba lagi": kembali ke deskripsi, reset hasil, fokus ke editor (kode tetap)
        const btnUlang = document.getElementById('btn-ulang');
        if (btnUlang) {
            btnUlang.addEventListener('click', () => {
                activateTab('left', 'deskripsi');
                resetOutputs();
                activateTab('out', 'terminal');
                lastSubmitFailed = false;
                updateGuide();
                document.getElementById('editor-wrap').scrollIntoView({ behavior: 'smooth', block: 'start' });
                if (editor) editor.focus();
            });
        }

        updateGuide();
    </script>
    @endpush
</x-layouts.app>