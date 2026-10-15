<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modul 04: Perulangan (Loops) - PyBeginner</title>

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
                <span class="text-gray-900 font-semibold">Modul 04</span>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-100 text-purple-700">Menengah Bawah</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                            <i data-lucide="check" class="w-3 h-3"></i> Selesai
                        </span>
                        <span class="text-xs text-gray-400 font-mono-code">MODUL 04</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-3">Perulangan (Loops)</h1>
                    <p class="text-gray-600 max-w-2xl text-sm leading-relaxed">
                        Otomatisasi tugas komputasi berulang menggunakan <code>for loop</code> & fungsi <code>range()</code>, iterasi koleksi data, <code>while loop</code>, kontrol <code>break</code> & <code>continue</code>, serta pola-pola perulangan umum.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="text-right mr-3">
                        <p class="text-xs text-gray-500 mb-1">Kemajuan Modul</p>
                        <p class="text-lg font-extrabold text-emerald-700">6/6 Materi</p>
                    </div>
                    <div class="w-14 h-14 rounded-full border-4 border-emerald-500 flex items-center justify-center">
                        <span class="text-sm font-extrabold text-emerald-700">100%</span>
                    </div>
                </div>
            </div>

            {{-- Stat mini --}}
            <div class="flex items-center gap-6 mt-6 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> 6 Materi</span>
                <span class="flex items-center gap-1.5"><i data-lucide="code" class="w-3.5 h-3.5"></i> 6 Latihan</span>
                <span class="flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5"></i> ±55 Menit</span>
                <span class="flex items-center gap-1.5"><i data-lucide="bar-chart-3" class="w-3.5 h-3.5"></i> Level: Menengah Bawah</span>
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
                            <p class="text-xs text-gray-400 mt-0.5">6 materi dalam modul ini</p>
                        </div>
                        <nav class="py-2">
                            <a href="#materi-1" class="sidebar-link active block px-5 py-3 text-sm transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">for loop & range()</p>
                                        <p class="text-[11px] text-gray-400">Deret angka berurutan</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-2" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Iterasi String & List</p>
                                        <p class="text-[11px] text-gray-400">Membaca elemen berurutan</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-3" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">while loop</p>
                                        <p class="text-[11px] text-gray-400">Perulangan berbasis kondisi</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-4" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">break & continue</p>
                                        <p class="text-[11px] text-gray-400">Kontrol interupsi alur loop</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-5" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Nested Loop</p>
                                        <p class="text-[11px] text-gray-400">Perulangan bersarang & matriks</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-6" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Pola Perulangan Umum</p>
                                        <p class="text-[11px] text-gray-400">Akumulator, max/min, counter</p>
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

                    {{-- ========== MATERI 1: for loop & range() ========== --}}
                    <article id="materi-1" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">1</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">for loop & range()</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Mengulang instruksi sejumlah hitungan tertentu dengan mudah</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            <strong>Perulangan (Looping)</strong> adalah kemampuan program untuk menjalankan sekumpulan instruksi berkali-kali secara otomatis. Di Python, <code>for loop</code> digunakan saat kita mengetahui berapa kali perulangan harus dijalankan atau saat kita ingin menelusuri data berurutan.
                        </p>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Mengenal Fungsi range()</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Fungsi bawaan <code>range()</code> menghasilkan deret angka aritmatika. Fungsi ini memiliki 3 variasi parameter:
                        </p>

                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Sintaks</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Keterangan</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Contoh Angka</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code text-xs text-emerald-700 border-b border-gray-100">range(stop)</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Mulai dari 0 sampai sebelum nilai <code>stop</code></td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">range(5) → 0, 1, 2, 3, 4</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code text-xs text-emerald-700 border-b border-gray-100">range(start, stop)</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Mulai dari nilai <code>start</code> sampai sebelum <code>stop</code></td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">range(1, 6) → 1, 2, 3, 4, 5</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code text-xs text-emerald-700 border-b border-gray-100">range(start, stop, step)</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Melompat sebanyak interval <code>step</code></td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">range(0, 10, 2) → 0, 2, 4, 6, 8</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>for_range.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code><span class="comment"># 1. Perulangan 5 kali (0 sampai 4)</span>
<span class="keyword">for</span> i <span class="keyword">in</span> <span class="function">range</span>(<span class="number">5</span>):
    <span class="function">print</span>(<span class="string">f"Iterasi ke-{i}"</span>)

<span class="function">print</span>(<span class="string">"-"</span> * <span class="number">20</span>)

<span class="comment"># 2. Tabel perkalian 7 sederhana (1 sampai 5)</span>
angka = <span class="number">7</span>
<span class="keyword">for</span> pengali <span class="keyword">in</span> <span class="function">range</span>(<span class="number">1</span>, <span class="number">6</span>):
    hasil = angka * pengali
    <span class="function">print</span>(<span class="string">f"{angka} x {pengali} = {hasil}"</span>)

<span class="function">print</span>(<span class="string">"-"</span> * <span class="number">20</span>)

<span class="comment"># 3. Hitung mundur dengan step negatif</span>
<span class="keyword">for</span> detik <span class="keyword">in</span> <span class="function">range</span>(<span class="number">3</span>, <span class="number">0</span>, -<span class="number">1</span>):
    <span class="function">print</span>(<span class="string">f"Mulai dalam {detik}..."</span>)
<span class="function">print</span>(<span class="string">"🚀 Peluncuran!"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Iterasi ke-0<br>Iterasi ke-1<br>Iterasi ke-2<br>Iterasi ke-3<br>Iterasi ke-4<br>--------------------<br>7 x 1 = 7<br>7 x 2 = 14<br>7 x 3 = 21<br>7 x 4 = 28<br>7 x 5 = 35<br>--------------------<br>Mulai dalam 3...<br>Mulai dalam 2...<br>Mulai dalam 1...<br>🚀 Peluncuran!</span>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
                            <div class="flex items-start gap-3">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-yellow-800 text-sm mb-1">Catatan Penting Batas Akhir (Stop Exclusive):</p>
                                    <p class="text-yellow-700 text-sm leading-relaxed m-0">
                                        Ingat bahwa angka pada parameter <code>stop</code> <strong>tidak akan pernah dicetak</strong>. Jadi jika kamu ingin mengulang angka 1 sampai 10, kamu harus menulis <code>range(1, 11)</code>!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 2: Iterasi string dan list ========== --}}
                    <article id="materi-2" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">2</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Iterasi String dan List</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Membaca elemen per elemen dari kumpulan data secara langsung</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Di Python, tipe data seperti <code>string</code> dan <code>list</code> bersifat <strong>iterable</strong> (dapat ditelusuri). Kita tidak memerlukan indeks angka manual untuk mengakses setiap elemennya — cukup gunakan sintaks <code>for item in koleksi:</code>.
                        </p>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>iterasi_data.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code><span class="comment"># 1. Iterasi setiap huruf dalam string</span>
kata = <span class="string">"PYTHON"</span>
<span class="keyword">for</span> huruf <span class="keyword">in</span> kata:
    <span class="function">print</span>(huruf, end=<span class="string">"-"</span>)
<span class="function">print</span>()  <span class="comment"># pindah baris</span>

<span class="comment"># 2. Iterasi setiap item dalam list</span>
daftar_bahasa = [<span class="string">"Python"</span>, <span class="string">"JavaScript"</span>, <span class="string">"Go"</span>, <span class="string">"Rust"</span>]
<span class="keyword">for</span> bahasa <span class="keyword">in</span> daftar_bahasa:
    <span class="function">print</span>(<span class="string">f"Sedang mempelajari: {bahasa}"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>P-Y-T-H-O-N-<br>Sedang mempelajari: Python<br>Sedang mempelajari: JavaScript<br>Sedang mempelajari: Go<br>Sedang mempelajari: Rust</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Mengambil Indeks & Nilai dengan enumerate()</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Jika kamu membutuhkan nomor urut indeks sekaligus nilainya saat looping, gunakan fungsi praktis <code>enumerate()</code>:
                        </p>

                        <div class="code-block mb-6">
                            <div class="code-header"><span>enumerate_demo.py</span></div>
                            <pre><code>juara = [<span class="string">"Adelia"</span>, <span class="string">"Budi"</span>, <span class="string">"Chandra"</span>]

<span class="comment"># Parameter start=1 membuat nomor urut dimulai dari 1 (bukan 0)</span>
<span class="keyword">for</span> nomor, nama <span class="keyword">in</span> <span class="function">enumerate</span>(juara, start=<span class="number">1</span>):
    <span class="function">print</span>(<span class="string">f"Juara {nomor}: {nama}"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Juara 1: Adelia<br>Juara 2: Budi<br>Juara 3: Chandra</span>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                            <div class="flex items-start gap-3">
                                <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"></i>
                                <p class="text-blue-800 text-sm leading-relaxed m-0">
                                    <strong>Pythonic Way:</strong> Hindari penulisan bergaya C seperti <code>for i in range(len(list)): print(list[i])</code> jika kamu hanya butuh nilainya. Menulis <code>for item in list:</code> jauh lebih ringkas, cepat, dan mudah dibaca!
                                </p>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 3: while loop ========== --}}
                    <article id="materi-3" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">3</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">while loop</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Perulangan dinamis yang terus berjalan selama kondisi logika bernilai True</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Berbeda dengan <code>for loop</code> yang jumlah perulangannya sudah ditentukan sejak awal, <strong>while loop</strong> akan terus mengulang blok kode selama suatu kondisi boolean tetap bernilai <code>True</code>. Perulangan baru akan berhenti saat kondisi tersebut berubah menjadi <code>False</code>.
                        </p>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>while_dasar.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code>hitung = <span class="number">1</span>

<span class="comment"># Berjalan selama hitung kurang dari atau sama dengan 5</span>
<span class="keyword">while</span> hitung <= <span class="number">5</span>:
    <span class="function">print</span>(<span class="string">f"Nomor antrean: A-0{hitung}"</span>)
    hitung += <span class="number">1</span>  <span class="comment"># PENTING: Wajib memperbarui nilai agar tidak infinite loop</span>

<span class="function">print</span>(<span class="string">"Semua antrean selesai dipanggil."</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Nomor antrean: A-01<br>Nomor antrean: A-02<br>Nomor antrean: A-03<br>Nomor antrean: A-04<br>Nomor antrean: A-05<br>Semua antrean selesai dipanggil.</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Tiga Bagian Utama while Loop:</h3>
                        <div class="grid sm:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <span class="text-xs font-bold text-emerald-700 uppercase block mb-1">1. Inisialisasi</span>
                                <p class="text-xs text-gray-600 m-0">Menyiapkan nilai awal variabel penghitung (counter) sebelum loop dimulai.</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <span class="text-xs font-bold text-blue-700 uppercase block mb-1">2. Kondisi Logika</span>
                                <p class="text-xs text-gray-600 m-0">Ekspresi pengujian yang dievaluasi sebelum setiap putaran loop dijalankan.</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <span class="text-xs font-bold text-purple-700 uppercase block mb-1">3. Pembaruan Nilai</span>
                                <p class="text-xs text-gray-600 m-0">Mengubah counter (misal <code>i += 1</code>) agar kondisi kelak mencapai <code>False</code>.</p>
                            </div>
                        </div>

                        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                            <div class="flex items-start gap-3">
                                <i data-lucide="alert-octagon" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-red-800 text-sm mb-1">Bahaya Infinite Loop (Perulangan Tak Henti):</p>
                                    <p class="text-red-700 text-sm leading-relaxed m-0">
                                        Jika kamu lupa menambahkan baris increment (seperti <code>hitung += 1</code>), kondisi akan selamanya bernilai <code>True</code> dan program akan hang/beku memakan CPU. Jika terjadi di terminal, tekan kombinasi tombol <kbd class="bg-white px-1.5 py-0.5 rounded border border-red-300 font-mono text-xs">Ctrl + C</kbd> untuk menghentikannya paksa.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 4: break & continue ========== --}}
                    <article id="materi-4" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">4</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">break & continue</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Mengendalikan dan memodifikasi alur eksekusi perulangan</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Kadang kala kita ingin menghentikan perulangan lebih cepat saat data yang dicari sudah ditemukan, atau kita ingin melompati iterasi tertentu tanpa menghentikan perulangan seluruhnya. Python menyediakan dua kata kunci kontrol:
                        </p>

                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="stop-circle" class="w-4 h-4 text-red-600"></i>
                                    <span class="font-bold text-sm text-red-900">break</span>
                                </div>
                                <p class="text-xs text-red-700 m-0 leading-relaxed">
                                    <strong>Menghentikan paksa</strong> seluruh perulangan seketika dan langsung keluar menuju baris kode di bawah loop.
                                </p>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="skip-forward" class="w-4 h-4 text-yellow-600"></i>
                                    <span class="font-bold text-sm text-yellow-900">continue</span>
                                </div>
                                <p class="text-xs text-yellow-700 m-0 leading-relaxed">
                                    <strong>Melompati sisa kode</strong> pada putaran iterasi saat ini, lalu langsung lanjut ke putaran berikutnya.
                                </p>
                            </div>
                        </div>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>kontrol_loop.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code><span class="comment"># Contoh 1: Mencari data dengan break</span>
daftar_nim = [<span class="string">"2101"</span>, <span class="string">"2102"</span>, <span class="string">"2103"</span>, <span class="string">"2104"</span>, <span class="string">"2105"</span>]
target = <span class="string">"2103"</span>

<span class="keyword">for</span> nim <span class="keyword">in</span> daftar_nim:
    <span class="function">print</span>(<span class="string">f"Memeriksa NIM: {nim}"</span>)
    <span class="keyword">if</span> nim == target:
        <span class="function">print</span>(<span class="string">f"🎯 NIM {target} DITEMUKAN! Pencarian dihentikan."</span>)
        <span class="keyword">break</span>

<span class="function">print</span>(<span class="string">"-"</span> * <span class="number">30</span>)

<span class="comment"># Contoh 2: Melewati bilangan genap dengan continue</span>
<span class="function">print</span>(<span class="string">"Bilangan ganjil antara 1 s/d 8:"</span>)
<span class="keyword">for</span> angka <span class="keyword">in</span> <span class="function">range</span>(<span class="number">1</span>, <span class="number">9</span>):
    <span class="keyword">if</span> angka % <span class="number">2</span> == <span class="number">0</span>:
        <span class="keyword">continue</span>  <span class="comment"># Lewati jika genap</span>
    <span class="function">print</span>(angka, end=<span class="string">" "</span>)
<span class="function">print</span>()</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Memeriksa NIM: 2101<br>Memeriksa NIM: 2102<br>Memeriksa NIM: 2103<br>🎯 NIM 2103 DITEMUKAN! Pencarian dihentikan.<br>------------------------------<br>Bilangan ganjil antara 1 s/d 8:<br>1 3 5 7</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Klausa for-else di Python</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Fitur unik Python lainnya adalah klausa <code>else</code> pada loop. Blok <code>else</code> hanya akan dijalankan jika perulangan selesai secara normal <strong>tanpa terhenti oleh break</strong>:
                        </p>

                        <div class="code-block">
                            <div class="code-header"><span>for_else.py</span></div>
                            <pre><code>peserta = [<span class="string">"Rina"</span>, <span class="string">"Dani"</span>, <span class="string">"Siti"</span>]
dicari = <span class="string">"Farhan"</span>

<span class="keyword">for</span> p <span class="keyword">in</span> peserta:
    <span class="keyword">if</span> p == dicari:
        <span class="function">print</span>(<span class="string">f"{dicari} hadir!"</span>)
        <span class="keyword">break</span>
<span class="keyword">else</span>:
    <span class="function">print</span>(<span class="string">f"Peserta atas nama {dicari} tidak ditemukan dalam daftar."</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Peserta atas nama Farhan tidak ditemukan dalam daftar.</span>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 5: Nested loop ========== --}}
                    <article id="materi-5" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">5</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Nested Loop (Perulangan Bersarang)</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Menjalankan perulangan di dalam perulangan lain untuk data 2 dimensi</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            <strong>Nested loop</strong> adalah struktur di mana sebuah loop diletakkan di dalam badan loop lainnya. Pola ini sangat lazim digunakan saat mengolah struktur data multi-dimensi seperti tabel (baris & kolom), matriks, koordinat grafis, atau pola karakter visual.
                        </p>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Prinsip Kerja Outer Loop & Inner Loop</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Untuk setiap <strong>satu putaran</strong> pada loop luar (outer loop), loop dalam (inner loop) akan berputar secara <strong>penuh dari awal hingga selesai</strong>.
                        </p>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>pola_bintang.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code><span class="comment"># Membuat pola segitiga siku-siku bintang</span>
tinggi = <span class="number">4</span>

<span class="keyword">for</span> baris <span class="keyword">in</span> <span class="function">range</span>(<span class="number">1</span>, tinggi + <span class="number">1</span>):
    <span class="comment"># Inner loop mencetak bintang sejumlah nomor baris</span>
    <span class="keyword">for</span> kolom <span class="keyword">in</span> <span class="function">range</span>(baris):
        <span class="function">print</span>(<span class="string">"⭐"</span>, end=<span class="string">" "</span>)
    <span class="function">print</span>()  <span class="comment"># Ganti baris setiap kali inner loop selesai</span>

<span class="function">print</span>(<span class="string">"-"</span> * <span class="number">20</span>)

<span class="comment"># Menelusuri matriks 2 dimensi</span>
matriks = [
    [<span class="number">1</span>, <span class="number">2</span>, <span class="number">3</span>],
    [<span class="number">4</span>, <span class="number">5</span>, <span class="number">6</span>]
]

<span class="keyword">for</span> r <span class="keyword">in</span> <span class="function">range</span>(<span class="function">len</span>(matriks)):
    <span class="keyword">for</span> c <span class="keyword">in</span> <span class="function">range</span>(<span class="function">len</span>(matriks[r])):
        <span class="function">print</span>(<span class="string">f"[{matriks[r][c]}]"</span>, end=<span class="string">" "</span>)
    <span class="function">print</span>()</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>⭐ <br>⭐ ⭐ <br>⭐ ⭐ ⭐ <br>⭐ ⭐ ⭐ ⭐ <br>--------------------<br>[1] [2] [3] <br>[4] [5] [6] </span>
                            </div>
                        </div>

                        <div class="bg-purple-50 border border-purple-200 rounded-xl p-5">
                            <div class="flex items-start gap-3">
                                <i data-lucide="clock" class="w-5 h-5 text-purple-600 shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-purple-900 text-sm mb-1">Perhatian Efisiensi (Kompleksitas O(N²)):</p>
                                    <p class="text-purple-700 text-sm leading-relaxed m-0">
                                        Perulangan bersarang berjalan sebanyak <code>M x N</code> kali. Jika outer loop mengulang 1.000 kali dan inner loop 1.000 kali, total operasinya mencapai <strong>1.000.000 kali</strong>! Hindari membuat nested loop yang terlalu dalam (3 level atau lebih) jika tidak benar-benar diperlukan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 6: Pola perulangan umum ========== --}}
                    <article id="materi-6" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">6</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Pola Perulangan Umum</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Pola algoritma dasar yang paling sering dipakai dalam pemecahan masalah coding</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Dalam dunia pemrograman profesional, perulangan hampir selalu digunakan dengan pola-pola standar tertentu. Menguasai pola-pola ini akan membuatmu mampu menyelesaikan berbagai studi kasus komputasi secara mandiri.
                        </p>

                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <span class="font-bold text-sm text-gray-900 block mb-1">1. Pola Akumulator (Penjumlahan)</span>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">Mengumpulkan atau menjumlahkan seluruh nilai elemen ke dalam satu variabel penampung.</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <span class="font-bold text-sm text-gray-900 block mb-1">2. Pola Pencari Nilai Ekstrem (Max / Min)</span>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">Membandingkan setiap elemen untuk menemukan nilai paling tinggi atau paling rendah.</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <span class="font-bold text-sm text-gray-900 block mb-1">3. Pola Penghitung Bersyarat (Counter)</span>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">Menghitung berapa banyak elemen yang memenuhi kriteria kondisi tertentu.</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <span class="font-bold text-sm text-gray-900 block mb-1">4. Pola Penyaring (Filtering)</span>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">Mengumpulkan elemen-elemen terpilih ke dalam list baru berdasarkan kriteria.</p>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Studi Kasus Lengkap: Analisis Nilai Mahasiswa</h3>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>analisis_nilai.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code>daftar_nilai = [<span class="number">75</span>, <span class="number">88</span>, <span class="number">62</span>, <span class="number">94</span>, <span class="number">55</span>, <span class="number">80</span>, <span class="number">91</span>]

<span class="comment"># Inisialisasi variabel pola</span>
total_skor = <span class="number">0</span>
jumlah_lulus = <span class="number">0</span>
nilai_tertinggi = daftar_nilai[<span class="number">0</span>]
nilai_terendah = daftar_nilai[<span class="number">0</span>]

<span class="keyword">for</span> nilai <span class="keyword">in</span> daftar_nilai:
    <span class="comment"># 1. Akumulator</span>
    total_skor += nilai
    
    <span class="comment"># 2. Counter bersyarat (lulus jika >= 70)</span>
    <span class="keyword">if</span> nilai >= <span class="number">70</span>:
        jumlah_lulus += <span class="number">1</span>
        
    <span class="comment"># 3. Pencari Max & Min</span>
    <span class="keyword">if</span> nilai > nilai_tertinggi:
        nilai_tertinggi = nilai
    <span class="keyword">if</span> nilai < nilai_terendah:
        nilai_terendah = nilai

rata_rata = total_skor / <span class="function">len</span>(daftar_nilai)

<span class="function">print</span>(<span class="string">f"Jumlah Mahasiswa : {len(daftar_nilai)}"</span>)
<span class="function">print</span>(<span class="string">f"Nilai Rata-rata  : {rata_rata:.2f}"</span>)
<span class="function">print</span>(<span class="string">f"Nilai Tertinggi  : {nilai_tertinggi}"</span>)
<span class="function">print</span>(<span class="string">f"Nilai Terendah   : {nilai_terendah}"</span>)
<span class="function">print</span>(<span class="string">f"Mahasiswa Lulus  : {jumlah_lulus} orang"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Jumlah Mahasiswa : 7<br>Nilai Rata-rata  : 77.86<br>Nilai Tertinggi  : 94<br>Nilai Terendah   : 55<br>Mahasiswa Lulus  : 5 orang</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <h4 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                                <i data-lucide="key" class="w-4 h-4 text-yellow-600"></i> Rangkuman Modul 04
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-2 m-0 pl-4">
                                <li><strong>for loop</strong> sangat ideal untuk mengulang instruksi dengan batas hitungan tertentu atau menelusuri urutan data.</li>
                                <li><strong>range(start, stop, step)</strong> menghasilkan deret angka di mana batas <code>stop</code> bersifat eksklusif (tidak ikut dicetak).</li>
                                <li><strong>while loop</strong> mengulang kode selama kondisi bernilai <code>True</code>; pastikan selalu ada pembaruan variabel counter untuk mencegah <em>infinite loop</em>.</li>
                                <li>Gunakan <strong>break</strong> untuk keluar seketika dan <strong>continue</strong> untuk melompati iterasi saat ini.</li>
                                <li><strong>Nested loop</strong> digunakan untuk struktur 2 dimensi seperti tabel, matriks, atau pola visual.</li>
                                <li>Pola akumulator, pencari ekstrem (max/min), dan counter adalah fondasi algoritma perulangan.</li>
                            </ul>
                        </div>
                    </article>

                    {{-- ========== NAVIGASI BAWAH ========== --}}
                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('modul.detail.03') }}"
                           class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700 bg-white border border-gray-200 px-5 py-2.5 rounded-xl transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Modul Sebelumnya (Modul 03)
                        </a>
                        <a href="{{ route('modul.detail.05') }}"
                           class="flex items-center gap-2 text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 px-5 py-2.5 rounded-xl transition-colors">
                            Modul Berikutnya (Modul 05) <i data-lucide="arrow-right" class="w-4 h-4"></i>
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
