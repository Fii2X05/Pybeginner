<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modul 02: Variabel dan Tipe Data - PyBeginner</title>

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
                <span class="text-gray-900 font-semibold">Modul 02</span>
            </nav>

            @if (session('status'))
                <div class="mb-6 bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    <span class="font-medium text-sm">{{ session('status') }}</span>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">Pemula</span>

                        {{-- Notif status di pojok kanan header --}}
                        @if (($status ?? 'belum') === 'selesai')
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                                <i data-lucide="check" class="w-3 h-3"></i> Selesai
                            </span>
                        @elseif (($status ?? 'belum') === 'berjalan')
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-700 bg-yellow-50 px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> Sedang Berjalan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                                <i data-lucide="lock" class="w-3 h-3"></i> Belum Mulai
                            </span>
                        @endif

                        <span class="text-xs text-gray-400 font-mono-code">MODUL 02</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-3">Variabel dan Tipe Data</h1>
                    <p class="text-gray-600 max-w-2xl text-sm leading-relaxed">
                        Memahami cara menyimpan data ke dalam variabel, tipe data angka (integer & float), teks (string), boolean, mengecek tipe data dengan <code>type()</code>, serta konversi tipe data dinamis.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="text-right mr-3">
                        <p class="text-xs text-gray-500 mb-1">Kemajuan Modul</p>
                        <p class="text-lg font-extrabold text-emerald-700">{{ $selesaiCount ?? 0 }}/{{ $totalMateri ?? 6 }} Materi</p>
                    </div>
                    <div class="w-14 h-14 rounded-full border-4 {{ ($status ?? 'belum') === 'selesai' ? 'border-emerald-500' : (($status ?? 'belum') === 'berjalan' ? 'border-yellow-400' : 'border-gray-200') }} flex items-center justify-center">
                        <span class="text-sm font-extrabold {{ ($status ?? 'belum') === 'selesai' ? 'text-emerald-700' : (($status ?? 'belum') === 'berjalan' ? 'text-yellow-600' : 'text-gray-400') }}">{{ $progress ?? 0 }}%</span>
                    </div>
                </div>
            </div>

            {{-- Stat mini --}}
            <div class="flex items-center gap-6 mt-6 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> 6 Materi</span>
                <span class="flex items-center gap-1.5"><i data-lucide="code" class="w-3.5 h-3.5"></i> 6 Latihan</span>
                <span class="flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5"></i> ±50 Menit</span>
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
                            <p class="text-xs text-gray-400 mt-0.5">6 materi dalam modul ini</p>
                        </div>
                        <nav class="py-2">
                            <a href="#materi-1" class="sidebar-link active block px-5 py-3 text-sm transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">Variabel & Penamaan</p>
                                        <p class="text-[11px] text-gray-400">Aturan & pembuatan variabel</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-2" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Integer & Float</p>
                                        <p class="text-[11px] text-gray-400">Bilangan bulat & desimal</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-3" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">String & Operasi Dasar</p>
                                        <p class="text-[11px] text-gray-400">Teks, indexing, & method</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-4" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Boolean & Perbandingan</p>
                                        <p class="text-[11px] text-gray-400">Logika True & False</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-5" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Mengecek Tipe Data</p>
                                        <p class="text-[11px] text-gray-400">Fungsi bawaan type()</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-6" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Konversi Tipe (Casting)</p>
                                        <p class="text-[11px] text-gray-400">int(), float(), str(), bool()</p>
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

                    {{-- ========== MATERI 1: Variabel & Aturan Penamaan ========== --}}
                    <article id="materi-1" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">1</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Variabel & Aturan Penamaan</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Memahami cara menyimpan data ke dalam memori program</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            <strong>Variabel</strong> adalah sebuah wadah atau tempat penyimpanan di dalam memori komputer yang diberi nama untuk menyimpan suatu nilai atau data. Dalam Python, kamu tidak perlu mendeklarasikan tipe data secara eksplisit — variabel akan otomatis tercipta saat kamu memberikan nilai kepadanya menggunakan operator penugasan (<code>=</code>).
                        </p>

                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 my-6">
                            <div class="flex items-start gap-3">
                                <i data-lucide="lightbulb" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-emerald-800 text-sm mb-1">Tahukah kamu?</p>
                                    <p class="text-emerald-700 text-sm leading-relaxed m-0">
                                        Python menggunakan konsep <em>Dynamic Typing</em>. Artinya, kamu bisa mengubah jenis data yang disimpan di dalam variabel kapan saja tanpa perlu khawatir terjadi error deklarasi!
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Sintaks & Deklarasi Variabel</h3>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>variabel_dasar.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code><span class="comment"># Menyimpan data ke dalam variabel</span>
nama = <span class="string">"Adelia Putri"</span>
umur = <span class="number">20</span>
ipk = <span class="number">3.85</span>
sedang_kuliah = <span class="keyword">True</span>

<span class="comment"># Menampilkan nilai variabel</span>
<span class="function">print</span>(<span class="string">"Nama Mahasiswa :"</span>, nama)
<span class="function">print</span>(<span class="string">"Usia           :"</span>, umur, <span class="string">"tahun"</span>)
<span class="function">print</span>(<span class="string">"Indeks Prestasi:"</span>, ipk)
<span class="function">print</span>(<span class="string">"Status Aktif   :"</span>, sedang_kuliah)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Nama Mahasiswa : Adelia Putri<br>Usia           : 20 tahun<br>Indeks Prestasi: 3.85<br>Status Aktif   : True</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Aturan Penamaan Variabel (Standar PEP 8)</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Agar kode program valid dan mudah dibaca oleh programmer lain, ikuti aturan wajib serta rekomendasi berikut:
                        </p>

                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                                    <span class="font-semibold text-sm text-gray-900">Karakter yang Diizinkan</span>
                                </div>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">
                                    Hanya boleh terdiri dari huruf (a-z, A-Z), angka (0-9), dan garis bawah (<code>_</code>).
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="alert-octagon" class="w-4 h-4 text-red-600"></i>
                                    <span class="font-semibold text-sm text-gray-900">Awal Karakter</span>
                                </div>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">
                                    <strong>HARUS</strong> diawali huruf atau garis bawah. <strong>TIDAK BOLEH</strong> diawali dengan angka.
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="type" class="w-4 h-4 text-blue-600"></i>
                                    <span class="font-semibold text-sm text-gray-900">Case-Sensitive</span>
                                </div>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">
                                    Huruf besar dan kecil dibedakan. Variabel <code>total</code>, <code>Total</code>, dan <code>TOTAL</code> adalah 3 variabel berbeda.
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="shield-alert" class="w-4 h-4 text-yellow-600"></i>
                                    <span class="font-semibold text-sm text-gray-900">Bukan Reserved Keyword</span>
                                </div>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">
                                    Tidak boleh memakai kata kunci bawaan Python seperti <code>class</code>, <code>def</code>, <code>if</code>, <code>for</code>, dll.
                                </p>
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                                <p class="font-semibold text-emerald-800 text-sm mb-2 flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Contoh Penamaan yang BENAR
                                </p>
                                <ul class="text-xs text-emerald-700 space-y-1.5 m-0 pl-4 font-mono-code">
                                    <li>nama_pengguna = "sukma"</li>
                                    <li>total_harga = 150000</li>
                                    <li>nilai_ujian_1 = 90</li>
                                    <li>_kode_rahasia = 9921</li>
                                </ul>
                            </div>
                            <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                                <p class="font-semibold text-red-800 text-sm mb-2 flex items-center gap-2">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i> Contoh Penamaan yang SALAH
                                </p>
                                <ul class="text-xs text-red-700 space-y-1.5 m-0 pl-4 font-mono-code">
                                    <li>1nama = "sukma" (diawali angka)</li>
                                    <li>total-harga = 150000 (ada tanda minus)</li>
                                    <li>nama pengguna = "sukma" (ada spasi)</li>
                                    <li>class = "A" (menggunakan keyword)</li>
                                </ul>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Penugasan Jamak (Multiple Assignment)</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Python memungkinkan kamu mengisi banyak variabel sekaligus dalam satu baris kode:
                        </p>

                        <div class="code-block">
                            <div class="code-header"><span>multiple_assignment.py</span></div>
                            <pre><code><span class="comment"># Memberi nilai berbeda untuk banyak variabel</span>
panjang, lebar, tinggi = <span class="number">10</span>, <span class="number">5</span>, <span class="number">2</span>
volume = panjang * lebar * tinggi
<span class="function">print</span>(<span class="string">f"Volume Balok: {volume} cm³"</span>)

<span class="comment"># Memberi nilai yang sama ke banyak variabel</span>
skor_a = skor_b = skor_c = <span class="number">100</span>
<span class="function">print</span>(skor_a, skor_b, skor_c)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Volume Balok: 100 cm³<br>100 100 100</span>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 2: Integer & Float ========== --}}
                    <article id="materi-2" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">2</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Integer & Float</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Bekerja dengan angka bilangan bulat dan bilangan pecahan desimal</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Di Python, data angka dibagi menjadi dua jenis utama yang paling sering dipakai: <strong>Integer (int)</strong> untuk bilangan bulat tanpa koma, dan <strong>Floating Point (float)</strong> untuk bilangan riil yang memiliki titik desimal.
                        </p>

                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                                <h4 class="font-bold text-blue-900 text-sm mb-2">Integer (int)</h4>
                                <p class="text-xs text-blue-700 mb-3 leading-relaxed">
                                    Bilangan bulat positif, negatif, atau nol tanpa desimal. Tidak memiliki batasan nilai maksimum (selama kapasitas memori RAM mencukupi).
                                </p>
                                <span class="font-mono-code text-xs bg-white px-2.5 py-1 rounded text-blue-800 border border-blue-200">
                                    Contoh: 10, -25, 0, 1000000
                                </span>
                            </div>
                            <div class="bg-purple-50 border border-purple-200 rounded-xl p-5">
                                <h4 class="font-bold text-purple-900 text-sm mb-2">Float (float)</h4>
                                <p class="text-xs text-purple-700 mb-3 leading-relaxed">
                                    Bilangan yang memiliki koma/pecahan desimal. Menggunakan tanda titik (<code>.</code>) sebagai pemisah desimal standar internasional.
                                </p>
                                <span class="font-mono-code text-xs bg-white px-2.5 py-1 rounded text-purple-800 border border-purple-200">
                                    Contoh: 3.14, -0.75, 2.0, 5e3
                                </span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Operator Aritmatika Lengkap</h3>

                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Operator</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Nama Operasi</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Contoh Kode</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Hasil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">+</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Penjumlahan</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">15 + 5</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-gray-900 border-b border-gray-100">20</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">-</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Pengurangan</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">20 - 8</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-gray-900 border-b border-gray-100">12</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">*</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Perkalian</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">4 * 6</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-gray-900 border-b border-gray-100">24</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">/</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Pembagian Riil (Float)</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">10 / 4</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-gray-900 border-b border-gray-100">2.5</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">//</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Pembagian Bulat (Floor Div)</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">10 // 4</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-gray-900 border-b border-gray-100">2</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">%</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Modulus (Sisa Bagi)</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">10 % 4</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-gray-900 border-b border-gray-100">2</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">**</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Perpangkatan</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">2 ** 4</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-gray-900 border-b border-gray-100">16</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>operasi_angka.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code><span class="comment"># Contoh perhitungan matematis</span>
harga_buku = <span class="number">45_000</span>       <span class="comment"># garis bawah (_) sebagai pemisah ribuan</span>
jumlah_beli = <span class="number">3</span>
diskon = <span class="number">0.10</span>             <span class="comment"># 10% diskon (tipe float)</span>

total_kotor = harga_buku * jumlah_beli
potongan = total_kotor * diskon
total_bayar = total_kotor - potongan

<span class="function">print</span>(<span class="string">f"Total Kotor : Rp{total_kotor}"</span>)
<span class="function">print</span>(<span class="string">f"Potongan    : Rp{potongan}"</span>)
<span class="function">print</span>(<span class="string">f"Harus Dibayar: Rp{total_bayar}"</span>)

<span class="comment"># Memeriksa bilangan ganjil atau genap dengan modulus</span>
angka = <span class="number">17</span>
sisa = angka % <span class="number">2</span>
<span class="function">print</span>(<span class="string">f"Sisa bagi 17 dengan 2 adalah: {sisa} (Ganjil)"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Total Kotor : Rp135000<br>Potongan    : Rp13500.0<br>Harus Dibayar: Rp121500.0<br>Sisa bagi 17 dengan 2 adalah: 1 (Ganjil)</span>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
                            <div class="flex items-start gap-3">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-yellow-800 text-sm mb-1">Catatan Penting Pembagian:</p>
                                    <p class="text-yellow-700 text-sm leading-relaxed m-0">
                                        Operator pembagian tunggal (<code>/</code>) di Python 3 akan <strong>selalu menghasilkan nilai bertipe float</strong>, meskipun hasilnya berupa angka bulat pas (contoh: <code>8 / 2</code> menghasilkan <code>4.0</code>, bukan <code>4</code>). Jika kamu ingin hasil berupa integer bulat, gunakan pembagian lantai (<code>//</code>).
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 3: String & Operasi Dasar String ========== --}}
                    <article id="materi-3" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">3</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">String & Operasi Dasar String</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Manipulasi teks, pemotongan (slicing), indeks, dan method bawaan</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            <strong>String (str)</strong> adalah tipe data untuk menyimpan teks atau barisan karakter. Di Python, string dapat ditulis menggunakan tanda kutip tunggal (<code>'...'</code>), tanda kutip ganda (<code>"..."</code>), atau tiga tanda kutip (<code>"""..."""</code>) untuk teks yang memanjang lebih dari satu baris (multiline).
                        </p>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Penggabungan & Pengulangan String</h3>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>gabung_string.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code>depan = <span class="string">"Belajar"</span>
belakang = <span class="string">"Python"</span>

<span class="comment"># 1. Menggabungkan string dengan operator +</span>
gabung = depan + <span class="string">" "</span> + belakang
<span class="function">print</span>(gabung)

<span class="comment"># 2. Mengulang string dengan operator *</span>
pembatas = <span class="string">"="</span> * <span class="number">20</span>
<span class="function">print</span>(pembatas)

<span class="comment"># 3. Menghitung panjang karakter dengan fungsi len()</span>
<span class="function">print</span>(<span class="string">f"Jumlah karakter '{gabung}':"</span>, <span class="function">len</span>(gabung))</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Belajar Python<br>====================<br>Jumlah karakter 'Belajar Python': 14</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Indexing & Slicing (Memotong Teks)</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Setiap karakter dalam string memiliki urutan indeks. Indeks Python dimulai dari <strong>0</strong> untuk karakter pertama, dan bisa menggunakan indeks negatif (<strong>-1</strong>) untuk membaca dari belakang:
                        </p>

                        <div class="overflow-x-auto mb-4">
                            <table class="w-full text-center text-sm border border-gray-200 rounded-xl overflow-hidden font-mono-code">
                                <tr class="bg-emerald-50 text-emerald-800 font-bold">
                                    <td class="p-2 border">Karakter</td>
                                    <td class="p-2 border">P</td>
                                    <td class="p-2 border">Y</td>
                                    <td class="p-2 border">T</td>
                                    <td class="p-2 border">H</td>
                                    <td class="p-2 border">O</td>
                                    <td class="p-2 border">N</td>
                                </tr>
                                <tr class="text-gray-700 bg-white">
                                    <td class="p-2 border font-semibold text-xs text-gray-400">Indeks Positif</td>
                                    <td class="p-2 border">0</td>
                                    <td class="p-2 border">1</td>
                                    <td class="p-2 border">2</td>
                                    <td class="p-2 border">3</td>
                                    <td class="p-2 border">4</td>
                                    <td class="p-2 border">5</td>
                                </tr>
                                <tr class="text-gray-600 bg-gray-50">
                                    <td class="p-2 border font-semibold text-xs text-gray-400">Indeks Negatif</td>
                                    <td class="p-2 border">-6</td>
                                    <td class="p-2 border">-5</td>
                                    <td class="p-2 border">-4</td>
                                    <td class="p-2 border">-3</td>
                                    <td class="p-2 border">-2</td>
                                    <td class="p-2 border">-1</td>
                                </tr>
                            </table>
                        </div>

                        <div class="code-block mb-6">
                            <div class="code-header"><span>slicing_string.py</span></div>
                            <pre><code>kata = <span class="string">"PYTHON"</span>

<span class="comment"># Indexing (mengambil 1 karakter)</span>
<span class="function">print</span>(kata[<span class="number">0</span>])     <span class="comment"># Karakter pertama: 'P'</span>
<span class="function">print</span>(kata[-<span class="number">1</span>])    <span class="comment"># Karakter terakhir: 'N'</span>

<span class="comment"># Slicing: kata[mulai:sampai_sebelum]</span>
<span class="function">print</span>(kata[<span class="number">0</span>:<span class="number">3</span>])   <span class="comment"># Dari indeks 0 sampai sebelum 3 -> 'PYT'</span>
<span class="function">print</span>(kata[<span class="number">2</span>:])    <span class="comment"># Dari indeks 2 sampai akhir -> 'THON'</span>
<span class="function">print</span>(kata[:<span class="number">4</span>])    <span class="comment"># Dari awal sampai sebelum 4 -> 'PYTH'</span></code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>P<br>N<br>PYT<br>THON<br>PYTH</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Method Bawaan String yang Sering Digunakan</h3>

                        <div class="code-block">
                            <div class="code-header"><span>string_methods.py</span></div>
                            <pre><code>pesan = <span class="string">"  Halo Rekan PyBeginner!  "</span>

<span class="comment"># Mengubah huruf besar / kecil</span>
<span class="function">print</span>(pesan.<span class="function">lower</span>())        <span class="comment"># Semua huruf kecil</span>
<span class="function">print</span>(pesan.<span class="function">upper</span>())        <span class="comment"># Semua huruf besar (KAPITAL)</span>

<span class="comment"># Menghapus spasi di ujung (trimming)</span>
pesan_bersih = pesan.<span class="function">strip</span>()
<span class="function">print</span>(<span class="string">f"'{pesan_bersih}'"</span>)

<span class="comment"># Mengganti kata (replace)</span>
<span class="function">print</span>(pesan_bersih.<span class="function">replace</span>(<span class="string">"Rekan"</span>, <span class="string">"Sobat"</span>))

<span class="comment"># Memecah string menjadi list kata (split)</span>
kata_list = pesan_bersih.<span class="function">split</span>(<span class="string">" "</span>)
<span class="function">print</span>(kata_list)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>  halo rekan pybeginner!  <br>  HALO REKAN PYBEGINNER!  <br>'Halo Rekan PyBeginner!'<br>Halo Sobat PyBeginner!<br>['Halo', 'Rekan', 'PyBeginner!']</span>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 4: Boolean & Operator Perbandingan ========== --}}
                    <article id="materi-4" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">4</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Boolean & Operator Perbandingan</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Evaluasi nilai kebenaran logika True dan False</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Tipe data <strong>Boolean (bool)</strong> hanya memiliki dua nilai yang mungkin: <code>True</code> (benar) atau <code>False</code> (salah). Di Python, nilai ini diawali dengan huruf kapital besar. Tipe data ini menjadi pondasi dalam membuat keputusan logika pemrograman (percabangan <code>if-else</code>).
                        </p>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Operator Perbandingan (Comparison Operators)</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Operator perbandingan digunakan untuk membandingkan dua nilai dan akan selalu menghasilkan nilai boolean (<code>True</code> atau <code>False</code>):
                        </p>

                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Operator</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Arti / Kondisi</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Contoh Ekspresi</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Hasil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">==</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Sama dengan (nilai identik)</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">5 == 5</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-emerald-600 border-b border-gray-100">True</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">!=</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Tidak sama dengan</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">5 != 3</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-emerald-600 border-b border-gray-100">True</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">&gt;</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Lebih besar dari</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">10 &gt; 20</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-red-600 border-b border-gray-100">False</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">&lt;</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Lebih kecil dari</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">10 &lt; 20</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-emerald-600 border-b border-gray-100">True</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">&gt;=</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Lebih besar atau sama dengan</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">15 &gt;= 15</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-emerald-600 border-b border-gray-100">True</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 font-mono-code font-bold text-emerald-700 border-b border-gray-100">&lt;=</td>
                                        <td class="px-4 py-3 text-gray-700 border-b border-gray-100">Lebih kecil atau sama dengan</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">8 &lt;= 5</td>
                                        <td class="px-4 py-3 font-mono-code text-xs font-semibold text-red-600 border-b border-gray-100">False</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>perbandingan_kondisi.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code>nilai_kkm = <span class="number">75</span>
nilai_siswa = <span class="number">82</span>

apakah_lulus = nilai_siswa >= nilai_kkm
apakah_sempurna = nilai_siswa == <span class="number">100</span>

<span class="function">print</span>(<span class="string">"Apakah siswa lulus?"</span>, apakah_lulus)
<span class="function">print</span>(<span class="string">"Apakah nilai sempurna?"</span>, apakah_sempurna)

<span class="comment"># Perbandingan teks (case-sensitive)</span>
<span class="function">print</span>(<span class="string">"python"</span> == <span class="string">"Python"</span>)  <span class="comment"># False karena huruf 'p' beda kapital</span>
<span class="function">print</span>(<span class="string">"apel"</span> != <span class="string">"jeruk"</span>)     <span class="comment"># True</span></code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Apakah siswa lulus? True<br>Apakah nilai sempurna? False<br>False<br>True</span>
                            </div>
                        </div>

                        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                            <div class="flex items-start gap-3">
                                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-red-800 text-sm mb-1">Perbedaan Krusial = vs == :</p>
                                    <p class="text-red-700 text-sm leading-relaxed m-0">
                                        Tanda <code>=</code> (satu sama dengan) adalah <strong>operator assignment</strong> untuk memasukkan nilai ke variabel. Sedangkan tanda <code>==</code> (dua sama dengan) adalah <strong>operator comparison</strong> untuk menguji kesamaan dua nilai. Jangan sampai tertukar!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 5: Mengecek Tipe dengan type() ========== --}}
                    <article id="materi-5" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">5</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Mengecek Tipe dengan type()</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Memeriksa jenis tipe data suatu nilai atau variabel saat program berjalan</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Karena Python menentukan tipe data secara otomatis (dynamic typing), sering kali kita perlu memeriksa tipe data suatu variabel untuk memastikan data tersebut sesuai dengan yang diharapkan. Python menyediakan fungsi bawaan <code>type()</code> untuk keperluan ini.
                        </p>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Penggunaan Fungsi type()</h3>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>cek_tipe_data.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code><span class="comment"># Berbagai macam variabel</span>
x = <span class="number">100</span>
y = <span class="number">3.14159</span>
teks = <span class="string">"Halo PyBeginner!"</span>
status = <span class="keyword">False</span>
daftar_hobi = [<span class="string">"Coding"</span>, <span class="string">"Membaca"</span>]

<span class="comment"># Mengecek tipe data masing-masing</span>
<span class="function">print</span>(x, <span class="string">"->"</span>, <span class="function">type</span>(x))
<span class="function">print</span>(y, <span class="string">"->"</span>, <span class="function">type</span>(y))
<span class="function">print</span>(teks, <span class="string">"->"</span>, <span class="function">type</span>(teks))
<span class="function">print</span>(status, <span class="string">"->"</span>, <span class="function">type</span>(status))
<span class="function">print</span>(daftar_hobi, <span class="string">"->"</span>, <span class="function">type</span>(daftar_hobi))</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>100 -&gt; &lt;class 'int'&gt;<br>3.14159 -&gt; &lt;class 'float'&gt;<br>Halo PyBeginner! -&gt; &lt;class 'str'&gt;<br>False -&gt; &lt;class 'bool'&gt;<br>['Coding', 'Membaca'] -&gt; &lt;class 'list'&gt;</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Alternatif Pengecekan: isinstance()</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Selain <code>type()</code>, dalam dunia industri dan penulisan kode profesional lebih direkomendasikan menggunakan fungsi <code>isinstance()</code>. Fungsi ini mengembalikan nilai <code>True</code> jika variabel sesuai dengan tipe yang dicocokkan:
                        </p>

                        <div class="code-block mb-6">
                            <div class="code-header"><span>isinstance_demo.py</span></div>
                            <pre><code>umur = <span class="number">20</span>

<span class="comment"># Menguji apakah 'umur' bertipe integer</span>
<span class="keyword">if</span> <span class="function">isinstance</span>(umur, <span class="builtin">int</span>):
    <span class="function">print</span>(<span class="string">"Valid: Data umur adalah bilangan bulat!"</span>)
<span class="keyword">else</span>:
    <span class="function">print</span>(<span class="string">"Peringatan: Data umur bukan integer!"</span>)

<span class="comment"># Bisa juga menguji terhadap beberapa tipe data sekaligus</span>
harga = <span class="number">50000.50</span>
<span class="function">print</span>(<span class="function">isinstance</span>(harga, (<span class="builtin">int</span>, <span class="builtin">float</span>)))  <span class="comment"># True jika int ATAU float</span></code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Valid: Data umur adalah bilangan bulat!<br>True</span>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                            <div class="flex items-start gap-3">
                                <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"></i>
                                <p class="text-blue-800 text-sm leading-relaxed m-0">
                                    <strong>Kenapa harus cek tipe?</strong> Ketika menerima data dari pengguna atau API eksternal, validasi tipe data sangat penting agar operasi seperti perkalian atau pemotongan karakter tidak memicu error yang menyebabkan aplikasi crash.
                                </p>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 6: Konversi Tipe Data (Casting) ========== --}}
                    <article id="materi-6" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">6</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Konversi Tipe Data (Casting)</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Mengubah format data dengan int(), float(), str(), dan bool()</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            <strong>Type Casting</strong> adalah proses mengubah suatu tipe data ke tipe data yang lain. Ada dua cara konversi di Python:
                        </p>

                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <span class="font-bold text-sm text-gray-900 block mb-1">1. Konversi Implisit (Otomatis)</span>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">
                                    Python otomatis mengubah tipe data tanpa campur tangan programmer untuk mencegah kehilangan data (misal: <code>int</code> + <code>float</code> otomatis menjadi <code>float</code>).
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <span class="font-bold text-sm text-gray-900 block mb-1">2. Konversi Eksplisit (Manual)</span>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">
                                    Dilakukan secara sadar oleh programmer menggunakan fungsi casting bawaan seperti <code>int()</code>, <code>float()</code>, <code>str()</code>, atau <code>bool()</code>.
                                </p>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Empat Fungsi Casting Utama</h3>

                        <div class="code-block mb-6">
                            <div class="code-header">
                                <span>fungsi_casting.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code><span class="comment"># 1. int() - mengubah float/string angka ke integer</span>
a = <span class="builtin">int</span>(<span class="number">9.85</span>)        <span class="comment"># dibulatkan ke bawah (truncate) -> 9</span>
b = <span class="builtin">int</span>(<span class="string">"42"</span>)        <span class="comment"># string teks angka -> 42</span>

<span class="comment"># 2. float() - mengubah integer/string ke desimal</span>
c = <span class="builtin">float</span>(<span class="number">10</span>)        <span class="comment"># 10.0</span>
d = <span class="builtin">float</span>(<span class="string">"3.14"</span>)    <span class="comment"># 3.14</span>

<span class="comment"># 3. str() - mengubah angka/boolean ke teks</span>
e = <span class="builtin">str</span>(<span class="number">100</span>)         <span class="comment"># "100"</span>
f = <span class="builtin">str</span>(<span class="keyword">True</span>)        <span class="comment"># "True"</span>

<span class="function">print</span>(a, b, c, d, e, f)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>9 42 10.0 3.14 100 True</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Konsep Nilai Truthy & Falsy dengan bool()</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Saat mengonversi nilai apa pun ke tipe boolean dengan <code>bool()</code>, Python menggunakan aturan <strong>Truthy</strong> dan <strong>Falsy</strong>:
                        </p>

                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                                <p class="font-semibold text-red-800 text-sm mb-2 flex items-center gap-2">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i> Nilai FALSY (Menghasilkan False)
                                </p>
                                <ul class="text-xs text-red-700 space-y-1.5 m-0 pl-4 font-mono-code">
                                    <li>bool(0) → False</li>
                                    <li>bool(0.0) → False</li>
                                    <li>bool("") → False (string kosong)</li>
                                    <li>bool(None) → False</li>
                                    <li>bool([]) → False (list kosong)</li>
                                </ul>
                            </div>
                            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                                <p class="font-semibold text-emerald-800 text-sm mb-2 flex items-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Nilai TRUTHY (Menghasilkan True)
                                </p>
                                <ul class="text-xs text-emerald-700 space-y-1.5 m-0 pl-4 font-mono-code">
                                    <li>bool(1) atau bool(-5) → True</li>
                                    <li>bool(3.14) → True</li>
                                    <li>bool("Halo") → True (ada teksnya)</li>
                                    <li>bool(" ") → True (spasi bukan kosong)</li>
                                    <li>bool([1, 2]) → True (ada isinya)</li>
                                </ul>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Kasus Nyata: Mengolah Hasil input()</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Semua data yang diambil lewat <code>input()</code> <strong>selalu berupa string</strong>. Oleh karena itu, casting sangat mutlak diperlukan sebelum melakukan perhitungan:
                        </p>

                        <div class="code-block mb-6">
                            <div class="code-header"><span>kalkulator_persegi.py</span></div>
                            <pre><code><span class="comment"># Mengambil input sisi dari pengguna</span>
sisi_teks = <span class="function">input</span>(<span class="string">"Masukkan panjang sisi (cm): "</span>)  <span class="comment"># contoh user mengetik 7</span>

<span class="comment"># Casting dari string ke float agar bisa menerima koma</span>
sisi = <span class="builtin">float</span>(sisi_teks)

luas = sisi * sisi
keliling = <span class="number">4</span> * sisi

<span class="function">print</span>(<span class="string">f"Luas Persegi    : {luas} cm²"</span>)
<span class="function">print</span>(<span class="string">f"Keliling Persegi: {keliling} cm"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT (contoh input 7):</p>
                                <span>Masukkan panjang sisi (cm): 7<br>Luas Persegi    : 49.0 cm²<br>Keliling Persegi: 28.0 cm</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <h4 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                                <i data-lucide="key" class="w-4 h-4 text-yellow-600"></i> Rangkuman Modul 02
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-2 m-0 pl-4">
                                <li><strong>Variabel</strong> adalah label penyimpanan memori di Python tanpa perlu deklarasi tipe secara kaku.</li>
                                <li>Aturan nama variabel: gunakan <strong>snake_case</strong>, jangan diawali angka, hindari reserved keyword.</li>
                                <li>Tipe data dasar: <strong>int</strong> (bulat), <strong>float</strong> (desimal), <strong>str</strong> (teks), <strong>bool</strong> (True/False).</li>
                                <li>Periksa tipe data menggunakan fungsi <strong>type()</strong> atau <strong>isinstance()</strong>.</li>
                                <li>Konversi tipe data menggunakan fungsi <strong>int()</strong>, <strong>float()</strong>, <strong>str()</strong>, dan <strong>bool()</strong>.</li>
                            </ul>
                        </div>
                    </article>

                    {{-- ========== NAVIGASI BAWAH ========== --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
                        <a href="{{ route('modul.detail.01') }}"
                           class="w-full sm:w-auto flex items-center justify-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700 bg-white border border-gray-200 px-5 py-2.5 rounded-xl transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Modul Sebelumnya (Modul 01)
                        </a>

                        @auth
                            <form action="{{ route('modul.toggle-complete', 2) }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                @if (($status ?? 'belum') === 'selesai')
                                    <button type="submit"
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-sm font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-5 py-2.5 rounded-xl transition-colors">
                                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i> Modul Selesai (Klik untuk Ulangi)
                                    </button>
                                @else
                                    <button type="submit"
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 rounded-xl transition-colors shadow-sm">
                                        <i data-lucide="check" class="w-4 h-4"></i> Tandai Modul Selesai
                                    </button>
                                @endif
                            </form>
                        @endauth

                        <a href="{{ route('modul.detail.03') }}"
                           class="w-full sm:w-auto flex items-center justify-center gap-2 text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 px-5 py-2.5 rounded-xl transition-colors">
                            Modul Selanjutnya (Modul 03) <i data-lucide="arrow-right" class="w-4 h-4"></i>
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
