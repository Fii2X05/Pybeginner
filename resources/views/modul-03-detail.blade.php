<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modul 03: Percabangan & Logika Kondisional - PyBeginner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .font-mono-code { font-family: 'Fira Code', ui-monospace, 'Courier New', monospace; }
        pre code { font-family: 'Fira Code', ui-monospace, monospace; font-size: 0.85rem; line-height: 1.7; }
        .prose code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 0.8rem; font-family: 'Fira Code', monospace; color: #0f766e; }
        .prose pre code { background: transparent; padding: 0; border-radius: 0; color: inherit; }
        .sidebar-link.active { background: linear-gradient(90deg, #ecfdf5 0%, #f0fdf4 100%); border-left: 3px solid #059669; color: #065f46; font-weight: 600; }
        .sidebar-link { border-left: 3px solid transparent; }
        .sidebar-link:hover:not(.active) { background: #f9fafb; border-left-color: #d1d5db; }
        .code-block { background: #1e293b; border-radius: 12px; overflow: hidden; }
        .code-block .code-header { background: #334155; padding: 10px 16px; display: flex; align-items: center; justify-content: space-between; }
        .code-block .code-header span { color: #94a3b8; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .code-block pre { padding: 20px; margin: 0; overflow-x: auto; }
        .code-block pre code { color: #e2e8f0; }
        .code-output { background: #0f172a; border-radius: 0 0 12px 12px; padding: 14px 20px; border-top: 1px solid #334155; }
        .code-output span { color: #4ade80; font-family: 'Fira Code', monospace; font-size: 0.82rem; }
        .keyword { color: #c084fc; }
        .string { color: #86efac; }
        .comment { color: #64748b; font-style: italic; }
        .function { color: #67e8f9; }
        .number { color: #fbbf24; }
        .builtin { color: #f472b6; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    {{-- ===================== NAVBAR ===================== --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-gray-100">
        <nav class="max-w-7xl mx-auto px-6 lg:px-10 h-16 flex items-center justify-between">
            <div class="flex items-center gap-10">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-8 w-auto">
                </a>
                <ul class="hidden md:flex items-center gap-7 text-sm font-medium text-gray-600">
                    <li><a href="{{ route('beranda') }}" class="hover:text-emerald-600 transition-colors">Beranda</a></li>
                    <li><a href="{{ route('modul.index') }}" class="text-gray-900 border-b-2 border-gray-900 pb-1 font-semibold">Modul Belajar</a></li>
                    <li><a href="{{ route('latihan.index') }}" class="hover:text-emerald-600 transition-colors">Latihan Coding</a></li>
                    <li><a href="#" class="hover:text-emerald-600 transition-colors">Riwayat Submission</a></li>
                </ul>
            </div>
            <div class="flex items-center gap-5">
                <button type="button" class="relative text-gray-500 hover:text-gray-700">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </button>
                <span class="hidden sm:inline text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Sukma Ananda' }}</span>
                <span class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center text-white">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </span>
            </div>
        </nav>
    </header>

    {{-- ===================== BREADCRUMB & HEADER ===================== --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-white via-emerald-50/70 to-emerald-50 border-b border-gray-100">
        <div class="hidden lg:block absolute top-0 right-0 w-96 h-96 bg-yellow-100/50 rounded-full blur-3xl z-0"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 py-8">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('modul.index') }}" class="hover:text-emerald-600 transition-colors flex items-center gap-1">
                    <i data-lucide="book-open" class="w-3.5 h-3.5"></i> Modul Belajar
                </a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
                <span class="text-gray-900 font-semibold">Modul 03</span>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">Pemula</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-700 bg-yellow-50 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> Sedang Berjalan
                        </span>
                        <span class="text-xs text-gray-400 font-mono-code">MODUL 03</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-3">Percabangan & Logika Kondisional</h1>
                    <p class="text-gray-600 max-w-2xl text-sm leading-relaxed">
                        Membangun alur logika keputusan menggunakan statement <code>if</code>, <code>if-else</code>, <code>if-elif-else</code>, operator logika (<code>and</code>, <code>or</code>, <code>not</code>), serta kondisi bersarang (nested if).
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="text-right mr-3">
                        <p class="text-xs text-gray-500 mb-1">Kemajuan Modul</p>
                        <p class="text-lg font-extrabold text-emerald-700">5/5 Materi</p>
                    </div>
                    <div class="w-14 h-14 rounded-full border-4 border-emerald-500 flex items-center justify-center">
                        <span class="text-sm font-extrabold text-emerald-700">100%</span>
                    </div>
                </div>
            </div>

            {{-- Stat mini --}}
            <div class="flex items-center gap-6 mt-6 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> 5 Materi</span>
                <span class="flex items-center gap-1.5"><i data-lucide="code" class="w-3.5 h-3.5"></i> 5 Latihan</span>
                <span class="flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5"></i> ±45 Menit</span>
                <span class="flex items-center gap-1.5"><i data-lucide="bar-chart-3" class="w-3.5 h-3.5"></i> Level: Pemula</span>
            </div>
        </div>
    </section>

    {{-- ===================== KONTEN UTAMA ===================== --}}
    <section class="bg-gray-50 py-10">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-10">
            <div class="flex flex-col lg:flex-row gap-6">

                {{-- ===== SIDEBAR: Daftar Materi ===== --}}
                <aside class="lg:w-60 shrink-0">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden sticky top-24">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h2 class="font-bold text-sm text-gray-900">Daftar Materi</h2>
                            <p class="text-xs text-gray-400 mt-0.5">5 materi dalam modul ini</p>
                        </div>
                        <nav class="py-2">
                            <a href="#materi-1" class="sidebar-link active block px-5 py-3 text-sm transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">Statement if</p>
                                        <p class="text-[11px] text-gray-400">Kondisi dasar & titik dua</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-2" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">if-else</p>
                                        <p class="text-[11px] text-gray-400">Dua jalur alternatif</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-3" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">if-elif-else</p>
                                        <p class="text-[11px] text-gray-400">Banyak kondisi beruntun</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-4" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Operator Logika</p>
                                        <p class="text-[11px] text-gray-400">and, or, dan not</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-5" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Nested if</p>
                                        <p class="text-[11px] text-gray-400">Percabangan bersarang</p>
                                    </div>
                                </div>
                            </a>
                        </nav>

                        <div class="px-5 py-4 border-t border-gray-100">
                            <a href="{{ route('modul.index') }}" class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700 transition-colors">
                                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Modul
                            </a>
                        </div>
                    </div>
                </aside>

                {{-- ===== KONTEN MATERI ===== --}}
                <main class="flex-1 min-w-0">

                    {{-- ========== MATERI 1: Statement if ========== --}}
                    <article id="materi-1" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">1</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Statement if</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Membuat keputusan paling mendasar berdasarkan kondisi kebenaran</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Secara alami, program berjalan baris demi baris dari atas ke bawah (sekuensial). Namun, dengan <strong>percabangan (decision making)</strong>, program dapat memilih untuk mengeksekusi sekumpulan kode <em>hanya jika</em> kondisi tertentu bernilai <strong>True</strong>.
                        </p>

                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 my-6">
                            <div class="flex items-start gap-3">
                                <i data-lucide="git-branch" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-emerald-800 text-sm mb-1">Anatomi Sintaks if:</p>
                                    <p class="text-emerald-700 text-sm leading-relaxed m-0 font-mono-code">
                                        if &lt;kondisi boolean&gt;:<br>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&lt;blok kode yang dijalankan jika True&gt;
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Contoh Penggunaan Statement if</h3>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>cek_suhu.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code>suhu_ruangan = <span class="number">34</span>

<span class="comment"># Memeriksa apakah suhu melebihi ambang batas</span>
<span class="keyword">if</span> suhu_ruangan > <span class="number">30</span>:
    <span class="function">print</span>(<span class="string">"Peringatan: Suhu ruangan terlalu panas!"</span>)
    <span class="function">print</span>(<span class="string">"Menyalakan AC secara otomatis..."</span>)

<span class="comment"># Baris ini di luar blok if (selalu dieksekusi)</span>
<span class="function">print</span>(<span class="string">"Pemeriksaan sensor suhu selesai."</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Peringatan: Suhu ruangan terlalu panas!<br>Menyalakan AC secara otomatis...<br>Pemeriksaan sensor suhu selesai.</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Dua Hal Wajib dalam Statement if:</h3>

                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-mono-code font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded text-sm">:</span>
                                    <span class="font-semibold text-sm text-gray-900">Tanda Titik Dua (Colon)</span>
                                </div>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">
                                    Wajib diletakkan di ujung baris kondisi <code>if</code>. Jika lupa, Python akan memunculkan <code>SyntaxError: invalid syntax</code>.
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-mono-code font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded text-sm">4 spasi</span>
                                    <span class="font-semibold text-sm text-gray-900">Indentasi Blok Kode</span>
                                </div>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">
                                    Semua baris aksi di dalam <code>if</code> wajib menjorok ke dalam (4 spasi). Baris yang kembali ke rata kiri dianggap sudah di luar blok <code>if</code>.
                                </p>
                            </div>
                        </div>

                        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                            <div class="flex items-start gap-3">
                                <i data-lucide="alert-octagon" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-red-800 text-sm mb-1">Kesalahan Pemula: Lupa Titik Dua</p>
                                    <p class="text-red-700 text-sm leading-relaxed m-0 font-mono-code">
                                        ❌ if skor >= 75&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# Error! Lupa tanda titik dua (:)<br>
                                        ✔️ if skor >= 75:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# Benar! Diakhiri dengan tanda :
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 2: if-else ========== --}}
                    <article id="materi-2" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">2</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Percabangan if-else</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Menyediakan jalur alternatif ketika kondisi bernilai False</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Jika statement <code>if</code> hanya menentukan apa yang harus dilakukan ketika kondisi benar (True), maka klausa <code>else</code> berfungsi menentukan tindakan apa yang harus diambil ketika kondisi tersebut <strong>salah (False)</strong>. Ini menjamin tepat satu dari dua pilihan akan selalu dieksekusi.
                        </p>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>kelulusan.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code>nilai_ujian = <span class="number">68</span>
kkm = <span class="number">75</span>

<span class="keyword">if</span> nilai_ujian >= kkm:
    <span class="function">print</span>(<span class="string">"🎉 Selamat! Anda dinyatakan LULUS."</span>)
    <span class="function">print</span>(<span class="string">"Silakan lanjut ke materi berikutnya."</span>)
<span class="keyword">else</span>:
    <span class="function">print</span>(<span class="string">"📚 Mohon maaf, Anda BELUM LULUS."</span>)
    <span class="function">print</span>(<span class="string">f"Nilai Anda {nilai_ujian}, kurang {kkm - nilai_ujian} poin dari KKM."</span>)
    <span class="function">print</span>(<span class="string">"Jadwalkan ujian remedial bersama asisten dosen."</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>📚 Mohon maaf, Anda BELUM LULUS.<br>Nilai Anda 68, kurang 7 poin dari KKM.<br>Jadwalkan ujian remedial bersama asisten dosen.</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Kondisi Singkat: Ternary Operator (One-Liner if-else)</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Untuk penugasan variabel sederhana berdasarkan kondisi, Python menyediakan sintaks ekspresi kondisional dalam satu baris (ternary operator):
                        </p>

                        <div class="code-block mb-6">
                            <div class="code-header"><span>ternary_operator.py</span></div>
                            <pre><code>angka = <span class="number">14</span>

<span class="comment"># Format: <nilai_jika_true> if <kondisi> else <nilai_jika_false></span>
status_angka = <span class="string">"Genap"</span> <span class="keyword">if</span> angka % <span class="number">2</span> == <span class="number">0</span> <span class="keyword">else</span> <span class="string">"Ganjil"</span>

<span class="function">print</span>(<span class="string">f"Angka {angka} adalah bilangan {status_angka}."</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Angka 14 adalah bilangan Genap.</span>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                            <div class="flex items-start gap-3">
                                <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"></i>
                                <p class="text-blue-800 text-sm leading-relaxed m-0">
                                    <strong>Ingat:</strong> Bagian <code>else</code> tidak memerlukan kondisi tambahan. <code>else</code> akan otomatis dijalankan setiap kali kondisi <code>if</code> di atasnya menghasilkan <code>False</code>.
                                </p>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 3: if-elif-else ========== --}}
                    <article id="materi-3" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">3</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Percabangan if-elif-else</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Mengevaluasi lebih dari dua pilihan kondisi secara bertingkat</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Ketika kita menghadapi masalah dengan <strong>tiga atau lebih kemungkinan</strong>, menggunakan <code>if-else</code> biasa saja tidak cukup. Kata kunci <code>elif</code> (singkatan dari <em>else if</em>) memungkinkan kita menguji serangkaian kondisi baru secara berurutan.
                        </p>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>konversi_nilai_huruf.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code>skor = <span class="number">83</span>

<span class="keyword">if</span> skor >= <span class="number">85</span>:
    grade = <span class="string">"A"</span>
    keterangan = <span class="string">"Sangat Memuaskan"</span>
<span class="keyword">elif</span> skor >= <span class="number">75</span>:
    grade = <span class="string">"B"</span>
    keterangan = <span class="string">"Baik"</span>
<span class="keyword">elif</span> skor >= <span class="number">60</span>:
    grade = <span class="string">"C"</span>
    keterangan = <span class="string">"Cukup"</span>
<span class="keyword">elif</span> skor >= <span class="number">45</span>:
    grade = <span class="string">"D"</span>
    keterangan = <span class="string">"Kurang"</span>
<span class="keyword">else</span>:
    grade = <span class="string">"E"</span>
    keterangan = <span class="string">"Gagal"</span>

<span class="function">print</span>(<span class="string">f"Skor Akhir : {skor}"</span>)
<span class="function">print</span>(<span class="string">f"Grade Huruf: {grade} ({keterangan})"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Skor Akhir : 83<br>Grade Huruf: B (Baik)</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Cara Kerja Evaluasi elif:</h3>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center justify-center shrink-0">1</span>
                                <p class="text-xs text-gray-700 m-0">Python menguji kondisi <code>if</code> pertama. Jika <code>True</code>, blok tersebut dijalankan dan <strong>seluruh rantai elif di bawahnya langsung diabaikan</strong>.</p>
                            </div>
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center justify-center shrink-0">2</span>
                                <p class="text-xs text-gray-700 m-0">Jika kondisi pertama <code>False</code>, barulah Python memeriksa <code>elif</code> berikutnya satu per satu dari atas ke bawah.</p>
                            </div>
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center justify-center shrink-0">3</span>
                                <p class="text-xs text-gray-700 m-0">Jika semua kondisi <code>if</code> dan <code>elif</code> bernilai <code>False</code>, blok penampung terakhir yaitu <code>else</code> yang akan dieksekusi.</p>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
                            <div class="flex items-start gap-3">
                                <i data-lucide="lightbulb" class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-yellow-800 text-sm mb-1">Tips Urutan Kondisi:</p>
                                    <p class="text-yellow-700 text-sm leading-relaxed m-0">
                                        Selalu susun kondisi dari yang <strong>paling spesifik / terbesar</strong> ke yang paling umum. Jika kamu menulis <code>if skor >= 60</code> sebelum <code>elif skor >= 85</code>, maka nilai 90 akan salah masuk ke kategori 60 terlebih dahulu!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 4: Operator Logika (and, or, not) ========== --}}
                    <article id="materi-4" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">4</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Operator Logika (and, or, not)</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Menggabungkan beberapa ekspresi kondisi menjadi satu keputusan komprehensif</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Sering kali suatu keputusan memerlukan pemenuhan lebih dari satu syarat sekaligus (misalnya: usia cukup <strong>DAN</strong> memiliki SIM). Python menyediakan tiga operator logika bawaan yang ditulis dengan kata bahasa Inggris sederhana: <code>and</code>, <code>or</code>, dan <code>not</code>.
                        </p>

                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Operator</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Aturan Kebenaran</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Contoh Kode</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Hasil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-purple-700 border-b border-gray-100">and</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Bernilai <strong>True</strong> hanya jika <em>semua</em> syarat True</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">True and False</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-red-600 border-b border-gray-100">False</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-purple-700 border-b border-gray-100">or</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Bernilai <strong>True</strong> jika <em>minimal salah satu</em> syarat True</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">True or False</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-emerald-600 border-b border-gray-100">True</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-purple-700 border-b border-gray-100">not</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Membalikkan nilai kebenaran (True ↔ False)</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">not True</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-red-600 border-b border-gray-100">False</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Studi Kasus: Sistem Beasiswa Kuliah</h3>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>seleksi_beasiswa.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code>ipk = <span class="number">3.75</span>
penghasilan_ortu = <span class="number">3_500_000</span>
prestasi_nasional = <span class="keyword">True</span>
terkena_sanksi = <span class="keyword">False</span>

<span class="comment"># Syarat: IPK tinggi DAN (ekonomi kurang ATAU punya prestasi) DAN TIDAK disanksi</span>
<span class="keyword">if</span> ipk >= <span class="number">3.5</span> <span class="keyword">and</span> (penghasilan_ortu <= <span class="number">4_000_000</span> <span class="keyword">or</span> prestasi_nasional) <span class="keyword">and</span> <span class="keyword">not</span> terkena_sanksi:
    <span class="function">print</span>(<span class="string">"✅ Memenuhi syarat beasiswa unggulan!"</span>)
<span class="keyword">else</span>:
    <span class="function">print</span>(<span class="string">"❌ Belum memenuhi kriteria beasiswa."</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>✅ Memenuhi syarat beasiswa unggulan!</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Fitur Eksklusif Python: Chained Comparison</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Di bahasa lain seperti C/Java, mengecek rentang angka harus ditulis <code>x &gt;= 10 and x &lt;= 20</code>. Di Python, kamu bisa menulisnya secara natural seperti rumus matematika:
                        </p>

                        <div class="code-block mb-6">
                            <div class="code-header"><span>chained_comparison.py</span></div>
                            <pre><code>usia = <span class="number">22</span>

<span class="comment"># Format ringkas khas Python:</span>
<span class="keyword">if</span> <span class="number">18</span> <= usia <= <span class="number">30</span>:
    <span class="function">print</span>(<span class="string">"Kategori: Pemuda Produktif"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Kategori: Pemuda Produktif</span>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 5: Kondisi Bersarang (Nested if) ========== --}}
                    <article id="materi-5" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">5</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Kondisi Bersarang (Nested if)</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Meletakkan statement if di dalam blok if lainnya untuk logika bertingkat</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            <strong>Nested if (if bersarang)</strong> adalah struktur percabangan di mana sebuah statement <code>if</code> berada di dalam blok tubuh statement <code>if</code> lainnya. Pola ini sangat berguna saat kamu perlu memvalidasi kondisi secara bertahap (hierarkis).
                        </p>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>atm_penarikan.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code>pin_benar = <span class="keyword">True</span>
saldo = <span class="number">500_000</span>
nominal_tarik = <span class="number">200_000</span>

<span class="comment"># Validasi Tahap 1: Verifikasi PIN</span>
<span class="keyword">if</span> pin_benar:
    <span class="function">print</span>(<span class="string">"PIN Berhasil diverifikasi."</span>)
    
    <span class="comment"># Validasi Tahap 2: Cek kecukupan saldo (Nested if)</span>
    <span class="keyword">if</span> nominal_tarik <= saldo:
        saldo -= nominal_tarik
        <span class="function">print</span>(<span class="string">f"Penarikan Rp{nominal_tarik} berhasil!"</span>)
        <span class="function">print</span>(<span class="string">f"Sisa saldo Anda: Rp{saldo}"</span>)
    <span class="keyword">else</span>:
        <span class="function">print</span>(<span class="string">"Gagal: Saldo rekening Anda tidak mencukupi."</span>)

<span class="keyword">else</span>:
    <span class="function">print</span>(<span class="string">"Gagal: PIN yang Anda masukkan salah!"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>PIN Berhasil diverifikasi.<br>Penarikan Rp200000 berhasil!<br>Sisa saldo Anda: Rp300000</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Kapan Menggunakan Nested if vs Operator and?</h3>

                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <p class="font-semibold text-gray-900 text-sm mb-2 flex items-center gap-2">
                                    <i data-lucide="git-commit" class="w-4 h-4 text-emerald-600"></i> Gunakan Nested if Jika:
                                </p>
                                <ul class="text-xs text-gray-600 space-y-1.5 m-0 pl-4">
                                    <li>Perlu pesan error yang berbeda untuk setiap tahap kegagalan.</li>
                                    <li>Tahap kedua memakan proses berat yang hanya boleh dihitung bila tahap pertama lolos.</li>
                                </ul>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <p class="font-semibold text-gray-900 text-sm mb-2 flex items-center gap-2">
                                    <i data-lucide="zap" class="w-4 h-4 text-purple-600"></i> Gunakan Operator and Jika:
                                </p>
                                <ul class="text-xs text-gray-600 space-y-1.5 m-0 pl-4">
                                    <li>Kedua kondisi bernilai setara dan tidak memerlukan umpan balik bertingkat.</li>
                                    <li>Ingin menjaga kode tetap datar tanpa indentasi yang terlalu dalam (<em>Flat is better than nested</em> - Zen of Python).</li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <h4 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                                <i data-lucide="key" class="w-4 h-4 text-yellow-600"></i> Rangkuman Modul 03
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-2 m-0 pl-4">
                                <li><strong>if</strong> digunakan untuk mengeksekusi blok kode jika suatu kondisi bernilai <code>True</code>.</li>
                                <li><strong>if-else</strong> membagi alur menjadi dua opsi pasti: jalur benar (if) dan jalur alternatif (else).</li>
                                <li><strong>if-elif-else</strong> mengevaluasi banyak kemungkinan secara terurut dari atas ke bawah.</li>
                                <li><strong>Operator logika</strong> (<code>and</code>, <code>or</code>, <code>not</code>) menggabungkan atau membalikkan kondisi boolean.</li>
                                <li><strong>Nested if</strong> memungkinkan pemeriksaan kondisi bertingkat di dalam blok percabangan lain.</li>
                            </ul>
                        </div>
                    </article>

                    {{-- ========== NAVIGASI BAWAH ========== --}}
                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('modul.detail.02') }}"
                           class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700 bg-white border border-gray-200 px-5 py-2.5 rounded-xl transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Modul Sebelumnya (Modul 02)
                        </a>
                        <a href="{{ route('modul.detail.04') }}"
                           class="flex items-center gap-2 text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 px-5 py-2.5 rounded-xl transition-colors">
                            Modul Selanjutnya (Modul 04) <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>

                </main>
            </div>
        </div>
    </section>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="border-t border-gray-100 py-14 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <div class="lg:col-span-2">
                <div class="mb-4">
                    <span class="text-lg font-bold text-gray-900">PyBeginner</span>
                </div>
                <p class="text-sm text-gray-600 max-w-xs leading-relaxed">
                    Platform interaktif modern untuk menguasai pemrograman Python mulai dari nol secara terstruktur dan ramah pemula.
                </p>
            </div>

            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Kurikulum</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-emerald-600">Python Dasar</a></li>
                    <li><a href="#" class="hover:text-emerald-600">Struktur Data</a></li>
                    <li><a href="#" class="hover:text-emerald-600">Algoritma & Logika</a></li>
                    <li><a href="#" class="hover:text-emerald-600">OOP Python</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Fitur Belajar</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-emerald-600">Pembelajaran Interaktif</a></li>
                    <li><a href="#" class="hover:text-emerald-600">Kuis & Tantangan</a></li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-10 mt-10 pt-6 border-t border-gray-100 text-xs text-gray-500">
            &copy; {{ date('Y') }} Tim PBL Kelompok 5 - SIB 3B
        </div>
    </footer>

    <script>
        lucide.createIcons();

        // Sidebar smooth scroll & active state
        document.querySelectorAll('.sidebar-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (!this.getAttribute('href').startsWith('#')) return;
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                document.querySelectorAll('.sidebar-link').forEach(function(l) {
                    l.classList.remove('active');
                });
                this.classList.add('active');
            });
        });

        // Auto-highlight sidebar on scroll
        const sections = document.querySelectorAll('article[id^="materi-"]');
        const navLinks = document.querySelectorAll('.sidebar-link[href^="#materi-"]');

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    navLinks.forEach(function(link) {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + entry.target.id) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }, { rootMargin: '-20% 0px -60% 0px' });

        sections.forEach(function(section) {
            observer.observe(section);
        });
    </script>
</body>
</html>
