<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $exercise->title }} - PyBeginner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-emerald-50 text-slate-800">

    <x-navbar-learner active="latihan" />

    <main class="mx-auto max-w-6xl px-6 py-6">
        <a href="{{ route('latihan.index') }}" class="text-sm text-emerald-700 hover:underline">&larr; Kembali ke daftar latihan</a>
        <h1 class="mt-2 mb-5 text-2xl font-extrabold">{{ $exercise->title }}</h1>

        <div class="grid gap-5 lg:grid-cols-2">

            {{-- KIRI: instruksi --}}
            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Deskripsi Tantangan</h2>
                <p class="mt-2 text-sm">{{ $exercise->description }}</p>

                @if ($exercise->instructions)
                    <h2 class="mt-5 text-xs font-semibold uppercase tracking-wide text-slate-500">Instruksi</h2>
                    <p class="mt-2 text-sm">{{ $exercise->instructions }}</p>
                @endif

                <h2 class="mt-5 text-xs font-semibold uppercase tracking-wide text-slate-500">Contoh Uji Coba</h2>
                <div class="mt-2 space-y-2">
                    @foreach ($samples as $i => $s)
                        <div class="rounded-lg border border-slate-200 p-3 font-mono text-xs">
                            <p class="font-sans font-semibold text-slate-500">Contoh {{ $i + 1 }}</p>
                            <p>Input: {{ $s->input }}</p>
                            <p>Output: {{ $s->expected_output }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- KANAN: editor + hasil --}}
            <section class="space-y-4">
                <div class="overflow-hidden rounded-2xl bg-slate-900 shadow-sm">
                    <div class="flex items-center justify-between px-4 py-2 text-xs text-slate-300">
                        <span>solution.py</span><span>Python 3</span>
                    </div>
                    <div id="editor" style="height: 320px;"></div>
                    <div class="flex justify-end gap-2 bg-slate-800 px-4 py-3">
                        <button id="btn-run" class="rounded-lg bg-slate-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-slate-500">Jalankan Kode</button>
                        <button id="btn-submit" class="rounded-lg bg-emerald-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-emerald-500">Kirim &amp; Nilai Jawaban</button>
                    </div>
                </div>

                <div id="hasil" class="rounded-2xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Hasil akan tampil di sini setelah kamu menjalankan atau mengirim kode.</p>
                </div>
            </section>
        </div>
    </main>

    {{-- Monaco lewat CDN (tidak perlu npm) --}}
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>
    <script>
        const EXERCISE_ID = {{ $exercise->id }};
        const STARTER = @json($exercise->starter_code ?? '');
        const USE_MOCK = true; // ganti false setelah backend teman siap
        let editor;

        require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' } });
        require(['vs/editor/editor.main'], function () {
            editor = monaco.editor.create(document.getElementById('editor'), {
                value: STARTER,
                language: 'python',
                theme: 'vs-dark',
                fontSize: 14,
                minimap: { enabled: false },
                automaticLayout: true,
            });
        });

        const hasilEl = document.getElementById('hasil');

        function esc(t) {
            return String(t ?? '').replace(/[&<>]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[c]));
        }

        function tampilkanHasil(data) {
            let html = `<p class="font-semibold">Skor: ${data.score} / 100
                <span class="text-sm font-normal text-slate-500">(${data.passed_tests}/${data.total_tests} test case lolos)</span></p>
                <div class="mt-3 space-y-2">`;
            data.results.forEach((r, i) => {
                const warna = r.is_passed ? 'border-emerald-300 bg-emerald-50' : 'border-rose-300 bg-rose-50';
                html += `<div class="rounded-lg border p-3 text-sm ${warna}">
                    <p class="font-semibold">Test Case ${i + 1}${r.is_hidden ? ' (tersembunyi)' : ''}: ${r.is_passed ? 'Lolos' : 'Gagal'}</p>`;
                if (!r.is_hidden) {
                    html += `<p class="mt-1 font-mono text-xs">Input: ${esc(r.input)}<br>Diharapkan: ${esc(r.expected)}<br>Keluaran: ${esc(r.actual)}</p>`;
                }
                if (r.error) html += `<p class="mt-1 font-mono text-xs text-rose-700">${esc(r.error)}</p>`;
                html += `</div>`;
            });
            hasilEl.innerHTML = html + '</div>';
        }

        async function kirim(url) {
            hasilEl.innerHTML = '<p class="text-sm text-slate-500">Memproses...</p>';
            if (USE_MOCK) {
                await new Promise(r => setTimeout(r, 600));
                return tampilkanHasil({
                    score: 66.67, passed_tests: 2, total_tests: 3,
                    results: [
                        { is_hidden: false, is_passed: true,  input: '4', expected: 'Genap',  actual: 'Genap' },
                        { is_hidden: false, is_passed: true,  input: '7', expected: 'Ganjil', actual: 'Ganjil' },
                        { is_hidden: true,  is_passed: false },
                    ],
                });
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
                tampilkanHasil(await res.json());
            } catch (e) {
                hasilEl.innerHTML = '<p class="text-sm text-rose-600">Gagal terhubung ke server.</p>';
            }
        }

        document.getElementById('btn-run').addEventListener('click', () => kirim('/run'));
        document.getElementById('btn-submit').addEventListener('click', () => kirim('{{ route('submissions.submit') }}'));
    </script>
</body>
</html>