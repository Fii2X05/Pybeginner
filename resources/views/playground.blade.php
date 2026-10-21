<x-layouts.app :title="$exercise->title" active="latihan">

    @php $badge = 'bg-emerald-100 text-emerald-800'; @endphp

    {{-- HEADER --}}
    <a href="{{ route('latihan.index', ['level' => $exercise->difficulty]) }}"
       class="text-sm font-medium text-emerald-700 hover:underline">&larr; Kembali ke daftar latihan</a>

    <div class="mb-6 mt-3 flex flex-wrap items-center gap-3">
        <h1 class="text-3xl font-extrabold text-slate-900">{{ $exercise->title }}</h1>
        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badge }}">
            {{ $levels[$exercise->difficulty]['label'] ?? $exercise->difficulty }}
        </span>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">

        {{-- KIRI: SOAL --}}
        <section class="h-fit rounded-3xl bg-white p-7 shadow-sm ring-1 ring-emerald-100">
            <h2 class="text-xs font-bold uppercase tracking-widest text-emerald-600">Deskripsi Tantangan</h2>
            <p class="mt-2 leading-relaxed text-slate-700">{{ $exercise->description }}</p>

            @if ($exercise->instructions)
                <h2 class="mt-6 text-xs font-bold uppercase tracking-widest text-emerald-600">Instruksi</h2>
                <p class="mt-2 leading-relaxed text-slate-700">{{ $exercise->instructions }}</p>
            @endif

            <h2 class="mt-6 text-xs font-bold uppercase tracking-widest text-emerald-600">Contoh Uji Coba</h2>
            <div class="mt-3 space-y-3">
                @foreach ($samples as $i => $s)
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-4 font-mono text-xs text-slate-700">
                        <p class="mb-2 font-sans text-xs font-semibold text-emerald-700">Contoh {{ $i + 1 }}</p>
                        <p class="whitespace-pre-line"><span class="text-slate-400">Input &nbsp;:</span> {{ $s->input }}</p>
                        <p class="mt-1"><span class="text-slate-400">Output :</span> {{ $s->expected_output }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- KANAN: EDITOR + HASIL + MATERI --}}
        <section class="space-y-5">
            <div id="editor-wrap" class="overflow-hidden rounded-3xl bg-slate-900 shadow-sm">
                <div class="flex items-center justify-between px-5 py-3 text-xs text-slate-300">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-yellow-300"></span>
                        <span class="ml-2 font-mono">solution.py</span>
                    </div>
                    <span>Python 3</span>
                </div>
                <div id="editor" style="height: 320px;"></div>
                <div class="flex justify-end gap-3 bg-slate-800 px-5 py-3">
                    <button id="btn-run"
                            class="rounded-xl border border-emerald-400/60 px-4 py-2 text-sm font-semibold text-emerald-300 transition hover:bg-emerald-400/10">
                        Jalankan Kode
                    </button>
                    <button id="btn-submit"
                            class="rounded-xl bg-yellow-400 px-5 py-2 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-yellow-300">
                        Kirim &amp; Nilai Jawaban
                    </button>
                </div>
            </div>

            <div id="hasil" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-emerald-100">
                <p class="text-sm text-slate-500">Hasil akan tampil di sini setelah kamu menjalankan atau mengirim kode.</p>
            </div>

            {{-- MATERI: tersembunyi, baru muncul kalau jawaban belum benar --}}
            @if ($lesson)
                <div id="materi" class="hidden rounded-3xl bg-white p-6 shadow-sm ring-2 ring-yellow-300">
                    <p class="text-xs font-bold uppercase tracking-widest text-yellow-600">Baca dulu materinya</p>
                    <h2 class="mt-1 text-lg font-bold text-slate-900">{{ $lesson->title }}</h2>
                    <div class="mt-3 text-sm leading-relaxed text-slate-700">
                        {!! preg_replace('~(https?://[^\s<]+)~', '<a href="$1" target="_blank" rel="noopener" class="text-emerald-700 underline break-all">$1</a>', nl2br(e($lesson->content))) !!}
                    </div>
                    <button id="btn-ulang"
                            class="mt-5 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500">
                        &larr; Sudah paham, coba lagi
                    </button>
                </div>
            @endif
        </section>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>
    <script>
        const EXERCISE_ID = {{ $exercise->id }};
        const STARTER = @json($exercise->starter_code ?? '');
        const URL_LIST = @json(route('latihan.index', ['level' => $exercise->difficulty]));
        const USE_MOCK = true; // ganti false setelah backend teman siap
        let editor;

        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            editor = monaco.editor.create(document.getElementById('editor'), {
                value: STARTER, language: 'python', theme: 'vs-dark',
                fontSize: 14, minimap: { enabled: false }, automaticLayout: true,
            });
        });

        const hasilEl  = document.getElementById('hasil');
        const materiEl = document.getElementById('materi'); // null kalau tidak ada materi
        const PLACEHOLDER = '<p class="text-sm text-slate-500">Hasil akan tampil di sini setelah kamu menjalankan atau mengirim kode.</p>';

        function esc(t) {
            return String(t ?? '').replace(/[&<>]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[c]));
        }

        function tampilkanHasil(data, isSubmit) {
            const sempurna = Number(data.score) >= 100;
            const persen = Math.max(0, Math.min(100, Number(data.score) || 0));

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
                            Belum tepat. Baca materi di bawah dulu, lalu coba lagi.
                       </div>`;
            }

            html += `<div class="mt-4 space-y-2">`;
            data.results.forEach((r, i) => {
                const warna = r.is_passed ? 'border-emerald-200 bg-emerald-50' : 'border-yellow-300 bg-yellow-50';
                html += `<div class="rounded-xl border p-3 text-sm ${warna}">
                    <p class="font-semibold text-slate-800">Test Case ${i + 1}${r.is_hidden ? ' (tersembunyi)' : ''}: ${r.is_passed ? 'Lolos' : 'Gagal'}</p>`;
                if (!r.is_hidden) {
                    html += `<p class="mt-1 font-mono text-xs text-slate-600">Input: ${esc(r.input)}<br>Diharapkan: ${esc(r.expected)}<br>Keluaran: ${esc(r.actual)}</p>`;
                }
                if (r.error) html += `<p class="mt-1 font-mono text-xs text-yellow-800">${esc(r.error)}</p>`;
                html += `</div>`;
            });
            hasilEl.innerHTML = html + '</div>';

            // materi hanya muncul setelah KIRIM dan jawaban belum sempurna
            if (materiEl) {
                const tampil = isSubmit && !sempurna;
                materiEl.classList.toggle('hidden', !tampil);
                if (tampil) materiEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        async function kirim(url, isSubmit) {
            hasilEl.innerHTML = '<p class="text-sm text-slate-500">Memproses...</p>';
            if (materiEl) materiEl.classList.add('hidden');

            if (USE_MOCK) {
                await new Promise(r => setTimeout(r, 600));
                return tampilkanHasil({
                    score: 66.67, passed_tests: 2, total_tests: 3,
                    results: [
                        { is_hidden: false, is_passed: true,  input: '4', expected: 'Genap',  actual: 'Genap' },
                        { is_hidden: false, is_passed: true,  input: '7', expected: 'Ganjil', actual: 'Ganjil' },
                        { is_hidden: true,  is_passed: false },
                    ],
                }, isSubmit);
            }
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
                tampilkanHasil(await res.json(), isSubmit);
            } catch (e) {
                hasilEl.innerHTML = '<p class="text-sm text-yellow-800">Gagal terhubung ke server.</p>';
            }
        }

        document.getElementById('btn-run').addEventListener('click', () => kirim('/run', false));
        document.getElementById('btn-submit').addEventListener('click', () => kirim('{{ route('submissions.submit') }}', true));

        // "Coba lagi": sembunyikan materi, reset hasil, kembali ke editor (kode tetap)
        const btnUlang = document.getElementById('btn-ulang');
        if (btnUlang) {
            btnUlang.addEventListener('click', () => {
                materiEl.classList.add('hidden');
                hasilEl.innerHTML = PLACEHOLDER;
                document.getElementById('editor-wrap').scrollIntoView({ behavior: 'smooth', block: 'start' });
                if (editor) editor.focus();
            });
        }
    </script>
    @endpush
</x-layouts.app>