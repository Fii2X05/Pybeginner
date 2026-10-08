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
<body class="bg-white text-gray-900 antialiased">

    {{-- ===================== NAVBAR ===================== --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-gray-100">
        <nav class="max-w-7xl mx-auto px-6 lg:px-10 h-16 flex items-center justify-between">
            <div class="flex items-center gap-10">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-8 w-auto">
                </a>

                <ul class="hidden md:flex items-center gap-7 text-sm font-medium text-gray-600">
                    <li><a href="{{ route('beranda') }}" class="hover:text-emerald-600 transition-colors">Beranda</a></li>
                    <li><a href="{{ route('modul.index') }}" class="text-gray-900 border-b-2 border-emerald-600 pb-1">Modul Belajar</a></li>
                    <li><a href="{{ route('latihan.index') }}" class="hover:text-emerald-600 transition-colors">Latihan Coding</a></li>
                    <li><a href="{{ route('submissions.history') }}" class="hover:text-emerald-600 transition-colors">Riwayat Submission</a></li>
                </ul>
            </div>

            <div class="flex items-center gap-5">
                <button type="button" class="relative text-gray-500 hover:text-gray-700">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <span class="hidden sm:inline text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Sukma Ananda' }}</span>
                <span class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center text-white">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </span>
            </div>
        </nav>
    </header>

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
                    <img src="{{ asset('images/mascot-python.png') }}" alt="" class="w-16 h-16 object-contain object-top">
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
                $totalModul = count($modulList ?? []) ?: 7;
                $modulSelesai = collect($modulList ?? [])->where('status', 'selesai')->count() ?: 2;
                $persenSelesai = $totalModul > 0 ? round($modulSelesai / $totalModul * 100) : 0;
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
                    <input type="text" placeholder="Cari nama modul atau topik pembelajaran..."
                           class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="flex items-center gap-2 overflow-x-auto">
                    <button type="button" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold text-gray-900 whitespace-nowrap">Semua Modul</button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 whitespace-nowrap">Sedang Berjalan (1)</button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 whitespace-nowrap">Belum Mulai (4)</button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 whitespace-nowrap">Selesai (2)</button>
                </div>

                <div class="relative">
                    <select class="appearance-none pl-4 pr-9 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option>Urutkan: Sesuai Kurikulum</option>
                        <option>Urutkan: Terbaru</option>
                        <option>Urutkan: A-Z</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>

            {{-- Grid modul --}}
            @php
                $modulList = $modulList ?? [
                    ['no' => 1, 'level' => 'Pemula', 'status' => 'selesai', 'title' => 'Dasar Python & Sintaks Awal', 'desc' => 'Mengenal struktur program Python, fungsi print(), komentar kode, dan aturan indentasi yang menjadi ciri khas Python.', 'materi' => 5, 'latihan' => 5, 'menit' => 45, 'progress' => 100],
                    ['no' => 2, 'level' => 'Pemula', 'status' => 'selesai', 'title' => 'Variabel dan Tipe Data', 'desc' => 'Memahami cara menyimpan data ke dalam variabel, tipe data angka (integer & float), teks (string), boolean, serta konversi tipe data dinamis.', 'materi' => 6, 'latihan' => 6, 'menit' => 60, 'progress' => 100],
                    ['no' => 3, 'level' => 'Pemula', 'status' => 'berjalan', 'title' => 'Percabangan & Logika Kondisional', 'desc' => 'Membangun logika keputusan menggunakan if, elif, dan else. Menggunakan operator logika and, or, not.', 'materi' => 5, 'latihan' => 5, 'menit' => 50, 'progress' => 60, 'materiAktif' => 'Materi: Latihan 03: Cek Bilangan Genap/Ganjil', 'selesaiCount' => 3],
                    ['no' => 4, 'level' => 'Menengah Bawah', 'status' => 'belum', 'title' => 'Perulangan (Loops)', 'desc' => 'Otomatisasi tugas berulang dengan for loop, fungsi range(), while loop, serta kontrol perulangan break dan continue.', 'materi' => 6, 'latihan' => 6, 'menit' => 65],
                    ['no' => 5, 'level' => 'Menengah', 'status' => 'belum', 'title' => 'Fungsi & Modularitas Kode', 'desc' => 'Membuat fungsi mandiri dengan def, parameter, argumen default, return value, dan memahami konsep local vs global scope.', 'materi' => 7, 'latihan' => 7, 'menit' => 75],
                    ['no' => 6, 'level' => 'Menengah', 'status' => 'belum', 'title' => 'Struktur Data Dasar', 'desc' => 'Mengelola kumpulan data terstruktur: operasi indexing & slicing pada List, ketetapan Tuple, pasangan key-value Dictionary, dan keunikan...', 'materi' => 8, 'latihan' => 8, 'menit' => 90],
                ];

                $levelColor = [
                    'Pemula' => 'bg-blue-100 text-blue-700',
                    'Menengah Bawah' => 'bg-purple-100 text-purple-700',
                    'Menengah' => 'bg-purple-100 text-purple-700',
                ];
            @endphp

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($modulList as $modul)
                    <div class="bg-white rounded-2xl border {{ $modul['status'] === 'berjalan' ? 'border-emerald-300 ring-1 ring-emerald-200' : 'border-gray-100' }} shadow-sm p-6 flex flex-col">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $levelColor[$modul['level']] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $modul['level'] }}
                            </span>

                            @if ($modul['status'] === 'selesai')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                                    <i data-lucide="check" class="w-3 h-3"></i> Selesai
                                </span>
                            @elseif ($modul['status'] === 'berjalan')
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-700 bg-yellow-50 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> Sedang Berjalan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                                    <i data-lucide="lock" class="w-3 h-3"></i> Belum Mulai
                                </span>
                            @endif
                        </div>

                        <p class="text-xs font-mono-code text-gray-400 mb-1">
                            MODUL {{ str_pad($modul['no'], 2, '0', STR_PAD_LEFT) }}
                            @if (!empty($modul['materiAktif'])) • MATERI AKTIF @endif
                        </p>
                        <h3 class="font-bold text-gray-900 mb-2">{{ $modul['title'] }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">{{ $modul['desc'] }}</p>

                        @if (!empty($modul['materiAktif']))
                            <div class="flex items-center gap-2 text-xs text-gray-600 bg-gray-50 border border-gray-100 rounded-lg px-3 py-2 mb-4">
                                <i data-lucide="play-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                {{ $modul['materiAktif'] }}
                            </div>
                        @endif

                        <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
                            <span class="flex items-center gap-1"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> {{ $modul['materi'] }} Materi • {{ $modul['latihan'] }} Latihan</span>
                            <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5"></i> {{ $modul['menit'] }} Menit</span>
                        </div>

                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="text-gray-500">
                                {{ $modul['selesaiCount'] ?? ($modul['status'] === 'selesai' ? $modul['materi'] : 0) }} / {{ $modul['materi'] }} Materi{{ $modul['status'] === 'selesai' ? ' Selesai' : '' }}
                            </span>
                            <span class="font-semibold text-gray-700">{{ $modul['progress'] ?? 0 }}%</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mb-4">
                            <div class="h-full rounded-full {{ $modul['status'] === 'selesai' ? 'bg-emerald-500' : ($modul['status'] === 'berjalan' ? 'bg-gradient-to-r from-emerald-500 to-yellow-400' : 'bg-gray-200') }}"
                                 style="width: {{ $modul['progress'] ?? 0 }}%"></div>
                        </div>

                        @if ($modul['status'] === 'selesai')
                            <a href="#"
                               class="w-full flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm py-2.5 rounded-lg transition-colors">
                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Tinjau Ulang
                            </a>
                        @elseif ($modul['status'] === 'berjalan')
                            <a href="#"
                               class="w-full flex items-center justify-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm py-2.5 rounded-lg transition-colors">
                                Lanjutkan Belajar <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        @else
                            <a href="#"
                               class="w-full flex items-center justify-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold text-sm py-2.5 rounded-lg transition-colors">
                                Mulai Modul
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="border-t border-gray-100 py-14 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <div class="lg:col-span-2">
                <div class="mb-4">
                    <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-8 w-auto">
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
    </script>
</body>
</html>