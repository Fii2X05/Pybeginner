<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modul Belajar Python - PyBeginner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .font-mono-code { font-family: ui-monospace, 'Fira Code', 'Courier New', monospace; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <x-navbar-learner active="modul" />

    {{-- ===================== HEADER MODUL ===================== --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-white via-emerald-50/70 to-emerald-50">
        <div class="hidden lg:block absolute top-0 right-0 w-96 h-96 bg-yellow-100/50 rounded-full blur-3xl z-0"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 py-10">
            <div class="flex items-center gap-3 mb-4 text-xs font-semibold">
                <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                    KURIKULUM TERSTRUKTUR PEMULA
                </span>
                <span class="text-gray-400">•</span>
                <span class="text-gray-500 font-mono-code">PYTHON 3.7.7</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/learning.png') }}" alt="" class="w-24 h-24 object-contain object-top">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Modul Belajar Python</h1>
                    </div>
                </div>

                <a href="{{ route('latihan.index') }}"
                   class="inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
                    <i data-lucide="square-code" class="w-4 h-4"></i>
                    Buka Latihan Python
                </a>
            </div>

            <p class="text-gray-600 max-w-2xl mb-8">
                Pelajari konsep pemrograman Python langkah demi langkah. Dilengkapi dengan materi teori ringkas, latihan interaktif langsung di browser, dan penilaian otomatis.
            </p>

            {{-- Stat cards --}}
            @php
                $totalModul   = 7;
                $modulSelesai = 2;
                $modulBerjalan = 1;
                $modulBelum   = 4;
                $persenSelesai = round($modulSelesai / $totalModul * 100); // 28%
            @endphp
            <div class="grid sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                        <i data-lucide="book-open" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 tracking-wide">TOTAL KURIKULUM</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $totalModul }} Modul</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                        <i data-lucide="badge-check" class="w-5 h-5 text-emerald-600"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 tracking-wide">MODUL SELESAI</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $modulSelesai }} <span class="text-base font-medium text-gray-400">/ {{ $totalModul }} ({{ $persenSelesai }}%)</span></p>
                    </div>
                </div>
            </div>

            {{-- Progress keseluruhan --}}
            <div>
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-gray-600">Kemajuan Keseluruhan Anda <span class="text-gray-400">• {{ $modulSelesai }} dari {{ $totalModul }} modul tuntas dipelajari</span></span>
                    <span class="font-mono-code text-emerald-700 font-semibold">{{ $persenSelesai }}% Selesai</span>
                </div>
                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-500 to-yellow-400 rounded-full" style="width: {{ $persenSelesai }}%"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== DAFTAR MODUL ===================== --}}
    <section class="bg-emerald-50 py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">

            {{-- Search & filter --}}
            <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-8">
                <div class="relative flex-1">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                    <input type="text" id="cari-modul" placeholder="Cari nama modul atau topik pembelajaran..."
                           class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="flex items-center gap-2 overflow-x-auto" id="filter-status">
                    <button type="button" data-filter="semua" class="filter-btn px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold text-gray-900 whitespace-nowrap">Semua Modul</button>
                    <button type="button" data-filter="berjalan" class="filter-btn px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 whitespace-nowrap">Sedang Berjalan ({{ $modulBerjalan }})</button>
                    <button type="button" data-filter="belum" class="filter-btn px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 whitespace-nowrap">Belum Mulai ({{ $modulBelum }})</button>
                    <button type="button" data-filter="selesai" class="filter-btn px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 whitespace-nowrap">Selesai ({{ $modulSelesai }})</button>
                </div>

                <div class="relative">
                    <select id="urutkan-modul" class="appearance-none pl-4 pr-9 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="kurikulum">Urutkan: Sesuai Kurikulum</option>
                        <option value="az">Urutkan: A-Z</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>

            {{-- Grid modul: 7 kartu modul --}}
            <div id="grid-modul" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 items-start">

                {{-- ============ MODUL 01 — Selesai ============ --}}
                <div class="modul-card bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col"
                     data-status="selesai" data-no="1"
                     data-title="dasar python & sintaks awal"
                     data-search="dasar python & sintaks awal mengenal struktur program python fungsi print komentar kode aturan indentasi">

                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">Pemula</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                            <i data-lucide="check" class="w-3 h-3"></i> Selesai
                        </span>
                    </div>

                    <p class="text-xs font-mono-code text-gray-400 mb-1">MODUL 01</p>
                    <h3 class="font-bold text-gray-900 mb-2">Dasar Python & Sintaks Awal</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">Mengenal struktur program Python, fungsi <code class="bg-gray-100 px-1 rounded text-xs">print()</code>, komentar kode, dan aturan indentasi yang menjadi ciri khas Python.</p>

                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
                        <span class="flex items-center gap-1"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> 5 Materi • 5 Latihan</span>
                        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5"></i> 45 Menit</span>
                    </div>

                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-gray-500">5 / 5 Materi Selesai</span>
                        <span class="font-semibold text-gray-700">100%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mb-4">
                        <div class="h-full rounded-full bg-emerald-500" style="width: 100%"></div>
                    </div>

                    <a href="{{ route('modul.detail.01') }}"
                       class="w-full flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm py-2.5 rounded-lg transition-colors">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Tinjau Ulang
                    </a>
                </div>

                {{-- ============ MODUL 02 — Selesai ============ --}}
                <div class="modul-card bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col"
                     data-status="selesai" data-no="2"
                     data-title="variabel dan tipe data"
                     data-search="variabel dan tipe data memahami cara menyimpan data integer float string boolean konversi tipe data dinamis">

                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">Pemula</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                            <i data-lucide="check" class="w-3 h-3"></i> Selesai
                        </span>
                    </div>

                    <p class="text-xs font-mono-code text-gray-400 mb-1">MODUL 02</p>
                    <h3 class="font-bold text-gray-900 mb-2">Variabel dan Tipe Data</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">Memahami cara menyimpan data ke dalam variabel, tipe data angka (integer & float), teks (string), boolean, serta konversi tipe data dinamis.</p>

                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
                        <span class="flex items-center gap-1"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> 6 Materi • 6 Latihan</span>
                        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5"></i> 60 Menit</span>
                    </div>

                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-gray-500">6 / 6 Materi Selesai</span>
                        <span class="font-semibold text-gray-700">100%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mb-4">
                        <div class="h-full rounded-full bg-emerald-500" style="width: 100%"></div>
                    </div>

                    <a href="#"
                       class="w-full flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm py-2.5 rounded-lg transition-colors">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Tinjau Ulang
                    </a>
                </div>

                {{-- ============ MODUL 03 — Sedang Berjalan ============ --}}
                <div class="modul-card bg-white rounded-2xl border border-emerald-300 ring-1 ring-emerald-200 shadow-sm p-6 flex flex-col"
                     data-status="berjalan" data-no="3"
                     data-title="percabangan & logika kondisional"
                     data-search="percabangan logika kondisional if elif else operator logika and or not">

                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">Pemula</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-700 bg-yellow-50 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> Sedang Berjalan
                        </span>
                    </div>

                    <p class="text-xs font-mono-code text-gray-400 mb-1">
                        MODUL 03 • MATERI AKTIF
                    </p>
                    <h3 class="font-bold text-gray-900 mb-2">Percabangan & Logika Kondisional</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">Membangun logika keputusan menggunakan <code class="bg-gray-100 px-1 rounded text-xs">if</code>, <code class="bg-gray-100 px-1 rounded text-xs">elif</code>, dan <code class="bg-gray-100 px-1 rounded text-xs">else</code>. Menggunakan operator logika and, or, not.</p>

                    <div class="flex items-center gap-2 text-xs text-gray-600 bg-gray-50 border border-gray-100 rounded-lg px-3 py-2 mb-4">
                        <i data-lucide="play-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                        Materi: Latihan 03: Cek Bilangan Genap/Ganjil
                    </div>

                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
                        <span class="flex items-center gap-1"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> 5 Materi • 5 Latihan</span>
                        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5"></i> 50 Menit</span>
                    </div>

                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-gray-500">3 / 5 Materi Selesai</span>
                        <span class="font-semibold text-gray-700">60%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mb-4">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-yellow-400" style="width: 60%"></div>
                    </div>

                    <a href="#"
                       class="w-full flex items-center justify-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm py-2.5 rounded-lg transition-colors">
                        Lanjutkan Belajar <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                {{-- ============ MODUL 04 — Belum Mulai ============ --}}
                <div class="modul-card bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col"
                     data-status="belum" data-no="4"
                     data-title="perulangan (loops)"
                     data-search="perulangan loops otomatisasi for loop range while break continue">

                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-100 text-purple-700">Menengah Bawah</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                            <i data-lucide="lock" class="w-3 h-3"></i> Belum Mulai
                        </span>
                    </div>

                    <p class="text-xs font-mono-code text-gray-400 mb-1">MODUL 04</p>
                    <h3 class="font-bold text-gray-900 mb-2">Perulangan (Loops)</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">Otomatisasi tugas berulang dengan for loop, fungsi <code class="bg-gray-100 px-1 rounded text-xs">range()</code>, while loop, serta kontrol perulangan break dan continue.</p>

                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
                        <span class="flex items-center gap-1"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> 6 Materi • 6 Latihan</span>
                        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5"></i> 65 Menit</span>
                    </div>

                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-gray-500">0 / 6 Materi</span>
                        <span class="font-semibold text-gray-700">0%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mb-4">
                        <div class="h-full rounded-full bg-gray-200" style="width: 0%"></div>
                    </div>

                    <a href="#"
                       class="w-full flex items-center justify-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold text-sm py-2.5 rounded-lg transition-colors">
                        Mulai Modul
                    </a>
                </div>

                {{-- ============ MODUL 05 — Belum Mulai ============ --}}
                <div class="modul-card bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col"
                     data-status="belum" data-no="5"
                     data-title="fungsi & modularitas kode"
                     data-search="fungsi modularitas kode def parameter argumen default return value local global scope">

                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-100 text-purple-700">Menengah</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                            <i data-lucide="lock" class="w-3 h-3"></i> Belum Mulai
                        </span>
                    </div>

                    <p class="text-xs font-mono-code text-gray-400 mb-1">MODUL 05</p>
                    <h3 class="font-bold text-gray-900 mb-2">Fungsi & Modularitas Kode</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">Membuat fungsi mandiri dengan <code class="bg-gray-100 px-1 rounded text-xs">def</code>, parameter, argumen default, return value, dan memahami konsep local vs global scope.</p>

                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
                        <span class="flex items-center gap-1"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> 7 Materi • 7 Latihan</span>
                        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5"></i> 75 Menit</span>
                    </div>

                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-gray-500">0 / 7 Materi</span>
                        <span class="font-semibold text-gray-700">0%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mb-4">
                        <div class="h-full rounded-full bg-gray-200" style="width: 0%"></div>
                    </div>

                    <a href="#"
                       class="w-full flex items-center justify-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold text-sm py-2.5 rounded-lg transition-colors">
                        Mulai Modul
                    </a>
                </div>

                {{-- ============ MODUL 06 — Belum Mulai ============ --}}
                <div class="modul-card bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col"
                     data-status="belum" data-no="6"
                     data-title="struktur data dasar"
                     data-search="struktur data dasar list tuple dictionary set indexing slicing key value">

                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-100 text-purple-700">Menengah</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                            <i data-lucide="lock" class="w-3 h-3"></i> Belum Mulai
                        </span>
                    </div>

                    <p class="text-xs font-mono-code text-gray-400 mb-1">MODUL 06</p>
                    <h3 class="font-bold text-gray-900 mb-2">Struktur Data Dasar</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">Mengelola kumpulan data terstruktur: operasi indexing & slicing pada List, ketetapan Tuple, pasangan key-value Dictionary, dan keunikan Set.</p>

                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
                        <span class="flex items-center gap-1"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> 8 Materi • 8 Latihan</span>
                        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5"></i> 90 Menit</span>
                    </div>

                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-gray-500">0 / 8 Materi</span>
                        <span class="font-semibold text-gray-700">0%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mb-4">
                        <div class="h-full rounded-full bg-gray-200" style="width: 0%"></div>
                    </div>

                    <a href="#"
                       class="w-full flex items-center justify-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold text-sm py-2.5 rounded-lg transition-colors">
                        Mulai Modul
                    </a>
                </div>

                {{-- ============ MODUL 07 — Belum Mulai ============ --}}
                <div class="modul-card bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col"
                     data-status="belum" data-no="7"
                     data-title="pengantar pandas & numpy"
                     data-search="pengantar pandas numpy analisis data array dataframe series manipulasi data">

                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-100 text-purple-700">Menengah</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                            <i data-lucide="lock" class="w-3 h-3"></i> Belum Mulai
                        </span>
                    </div>

                    <p class="text-xs font-mono-code text-gray-400 mb-1">MODUL 07</p>
                    <h3 class="font-bold text-gray-900 mb-2">Pengantar Pandas & NumPy</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">Pengenalan library analisis data Python: membuat dan memanipulasi array NumPy, serta bekerja dengan DataFrame dan Series pada Pandas.</p>

                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
                        <span class="flex items-center gap-1"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> 6 Materi • 5 Latihan</span>
                        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5"></i> 80 Menit</span>
                    </div>

                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-gray-500">0 / 6 Materi</span>
                        <span class="font-semibold text-gray-700">0%</span>
                    </div>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mb-4">
                        <div class="h-full rounded-full bg-gray-200" style="width: 0%"></div>
                    </div>

                    <a href="#"
                       class="w-full flex items-center justify-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold text-sm py-2.5 rounded-lg transition-colors">
                        Mulai Modul
                    </a>
                </div>

            </div>

            {{-- Kosong: hasil filter/pencarian tidak ada yang cocok --}}
            <div id="modul-kosong" class="hidden bg-white rounded-2xl border border-gray-100 p-10 text-center text-gray-600">
                Tidak ada modul yang cocok dengan pencarian atau filter kamu.
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

        // Filter status, pencarian, dan pengurutan kartu modul (di sisi browser)
        (function () {
            const grid = document.getElementById('grid-modul');
            if (!grid) return;

            const cards = Array.from(grid.querySelectorAll('.modul-card'));
            const cari = document.getElementById('cari-modul');
            const urut = document.getElementById('urutkan-modul');
            const tombol = document.querySelectorAll('.filter-btn');
            const kosong = document.getElementById('modul-kosong');
            let filterAktif = 'semua';

            function terapkan() {
                const q = cari.value.trim().toLowerCase();
                let tampil = 0;

                cards.forEach(function (c) {
                    const cocokStatus = filterAktif === 'semua' || c.dataset.status === filterAktif;
                    const cocokCari = q === '' || c.dataset.search.includes(q);
                    const lihat = cocokStatus && cocokCari;
                    c.classList.toggle('hidden', !lihat);
                    if (lihat) tampil++;
                });

                const urutan = cards.slice().sort(function (a, b) {
                    return urut.value === 'az'
                        ? a.dataset.title.localeCompare(b.dataset.title)
                        : Number(a.dataset.no) - Number(b.dataset.no);
                });
                urutan.forEach(function (c) { grid.appendChild(c); });

                kosong.classList.toggle('hidden', tampil !== 0 || cards.length === 0);
            }

            tombol.forEach(function (b) {
                b.addEventListener('click', function () {
                    filterAktif = b.dataset.filter;
                    tombol.forEach(function (x) {
                        const aktif = x === b;
                        x.classList.toggle('bg-white', aktif);
                        x.classList.toggle('border', aktif);
                        x.classList.toggle('border-gray-200', aktif);
                        x.classList.toggle('font-semibold', aktif);
                        x.classList.toggle('text-gray-900', aktif);
                        x.classList.toggle('font-medium', !aktif);
                        x.classList.toggle('text-gray-600', !aktif);
                    });
                    terapkan();
                });
            });

            cari.addEventListener('input', terapkan);
            urut.addEventListener('change', terapkan);
        })();
    </script>
</body>
</html>
