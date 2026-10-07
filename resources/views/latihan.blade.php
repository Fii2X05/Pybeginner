<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Latihan Coding - PyBeginner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-emerald-50 text-slate-800">

    <x-navbar-learner active="latihan" />

    <main class="mx-auto max-w-6xl px-6 py-10">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold">Latihan Coding Python</h1>
            <p class="mt-2 text-slate-600">
                Asah logika dan pemahaman sintaksis Python dengan latihan interaktif yang praktis dan terstruktur.
            </p>
        </div>

        <div class="mb-6 rounded-2xl bg-white p-4 shadow-sm">
            <input id="cari" type="text" placeholder="Cari latihan soal (misal: if-else, looping, fungsi)..."
                   class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm outline-none focus:border-emerald-500">
        </div>

        <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
            Daftar Latihan ({{ $exercises->count() }} soal)
        </p>

        <div id="daftar" class="space-y-3">
            @forelse ($exercises as $i => $ex)
                @php
                    $level = [
                        'easy'   => ['Pemula', 'bg-emerald-100 text-emerald-700'],
                        'medium' => ['Menengah', 'bg-amber-100 text-amber-700'],
                        'hard'   => ['Sulit', 'bg-rose-100 text-rose-700'],
                    ][$ex->difficulty] ?? [ucfirst($ex->difficulty), 'bg-slate-100 text-slate-700'];
                @endphp
                <div class="item flex items-center justify-between rounded-xl bg-white px-5 py-4 shadow-sm"
                     data-teks="{{ strtolower($ex->title . ' ' . $ex->description) }}">
                    <div>
                        <p class="font-semibold">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}. {{ $ex->title }}
                            <span class="ml-2 rounded-full px-2 py-0.5 text-xs font-medium {{ $level[1] }}">{{ $level[0] }}</span>
                        </p>
                        <p class="mt-1 text-sm text-slate-500">{{ $ex->description }}</p>
                    </div>
                    <a href="{{ route('latihan.show', $ex->slug) }}"
                       class="rounded-lg bg-yellow-400 px-4 py-1.5 text-sm font-semibold text-slate-900 hover:bg-yellow-300">
                        Mulai
                    </a>
                </div>
            @empty
                <p class="rounded-xl bg-white p-6 text-center text-slate-500">
                    Belum ada latihan. Jalankan: php artisan db:seed --class=LatihanSeeder
                </p>
            @endforelse
        </div>
    </main>

    <footer class="mt-10 border-t border-slate-200 bg-white py-6 text-center text-xs text-slate-500">
        © 2026 Tim PBL Kelompok 5 - SIB 3B
    </footer>

    <script>
        // Filter daftar soal di sisi browser
        document.getElementById('cari').addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.item').forEach(el => {
                el.style.display = el.dataset.teks.includes(q) ? '' : 'none';
            });
        });
    </script>
</body>
</html>