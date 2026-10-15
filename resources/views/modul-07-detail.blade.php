<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modul 07: Pengantar Pandas & NumPy - PyBeginner</title>

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
                <span class="text-gray-900 font-semibold">Modul 07</span>
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
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-100 text-purple-700">Menengah</span>

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

                        <span class="text-xs text-gray-400 font-mono-code">MODUL 07</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-3">Pengantar Pandas & NumPy</h1>
                    <p class="text-gray-600 max-w-2xl text-sm leading-relaxed">
                        Pengenalan library fundamental analisis data di Python: membuat dan memanipulasi array multidimensi dengan <code>NumPy</code>, serta bekerja dengan <code>Series</code> dan <code>DataFrame</code> menggunakan <code>Pandas</code>.
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
                <span class="flex items-center gap-1.5"><i data-lucide="code" class="w-3.5 h-3.5"></i> Data Science</span>
                <span class="flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5"></i> ±60 Menit</span>
                <span class="flex items-center gap-1.5"><i data-lucide="bar-chart-3" class="w-3.5 h-3.5"></i> Level: Menengah</span>
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
                                        <p class="font-semibold text-gray-900 text-sm">Apa itu NumPy?</p>
                                        <p class="text-[11px] text-gray-400">Dasar ndarray & kecepatan</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-2" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Manipulasi Array</p>
                                        <p class="text-[11px] text-gray-400">Indexing, slicing, reshape</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-3" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Operasi NumPy</p>
                                        <p class="text-[11px] text-gray-400">Broadcasting & statistik</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-4" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Series Pandas</p>
                                        <p class="text-[11px] text-gray-400">Data 1D berlabel</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-5" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">DataFrame Pandas</p>
                                        <p class="text-[11px] text-gray-400">Tabel data 2D</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-6" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Filter & Agregasi</p>
                                        <p class="text-[11px] text-gray-400">CSV, filtering, groupby</p>
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

                    {{-- ========== MATERI 1: Apa itu NumPy? ========== --}}
                    <article id="materi-1" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">1</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Apa itu NumPy?</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Library fundamental komputasi ilmiah dan array N-dimensi di Python</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            <strong>NumPy</strong> (Numerical Python) menyediakan struktur data <code>ndarray</code> yang jauh lebih cepat dan hemat memori dibandingkan list Python biasa untuk komputasi numerik skala besar.
                        </p>

                        <div class="code-block my-4">
                            <div class="code-header">
                                <span>Python &bull; Membuat Array NumPy</span>
                            </div>
                            <pre><code><span class="keyword">import</span> numpy <span class="keyword">as</span> np

<span class="comment"># Array 1D</span>
arr1d = np.array([<span class="number">1</span>, <span class="number">2</span>, <span class="number">3</span>, <span class="number">4</span>, <span class="number">5</span>])
<span class="function">print</span>(<span class="string">"Array 1D:"</span>, arr1d)
<span class="function">print</span>(<span class="string">"Bentuk  :"</span>, arr1d.shape)

<span class="comment"># Array 2D (Matriks)</span>
arr2d = np.array([[<span class="number">1</span>, <span class="number">2</span>, <span class="number">3</span>], [<span class="number">4</span>, <span class="number">5</span>, <span class="number">6</span>]])
<span class="function">print</span>(<span class="string">"Array 2D:\n"</span>, arr2d)
<span class="function">print</span>(<span class="string">"Dimensi :"</span>, arr2d.ndim)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Array 1D: [1 2 3 4 5]<br>Bentuk  : (5,)<br>Array 2D:<br>[[1 2 3]<br> [4 5 6]]<br>Dimensi : 2</span>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 2: Membuat & Memanipulasi Array ========== --}}
                    <article id="materi-2" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">2</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Membuat & Memanipulasi Array</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Pembuatan array khusus, indexing 2D, slicing, dan reshape</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            NumPy menyediakan fungsi instan seperti <code>zeros()</code>, <code>ones()</code>, <code>arange()</code>, serta metode transformasi bentuk <code>reshape()</code>.
                        </p>

                        <div class="code-block my-4">
                            <div class="code-header">
                                <span>Python &bull; Reshape & Indexing 2D</span>
                            </div>
                            <pre><code><span class="keyword">import</span> numpy <span class="keyword">as</span> np

<span class="comment"># Membuat array 0 s.d. 11 lalu ubah bentuk ke 3x4</span>
data = np.arange(<span class="number">12</span>).reshape(<span class="number">3</span>, <span class="number">4</span>)
<span class="function">print</span>(data)

<span class="comment"># Akses baris 1, kolom 2</span>
<span class="function">print</span>(<span class="string">"Elemen [1, 2]:"</span>, data[<span class="number">1</span>, <span class="number">2</span>])

<span class="comment"># Slicing semua baris, kolom ke-0</span>
<span class="function">print</span>(<span class="string">"Kolom pertama :"</span>, data[:, <span class="number">0</span>])</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>[[ 0  1  2  3]<br> [ 4  5  6  7]<br> [ 8  9 10 11]]<br>Elemen [1, 2]: 6<br>Kolom pertama : [0 4 8]</span>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 3: Operasi Array NumPy ========== --}}
                    <article id="materi-3" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">3</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Operasi Array NumPy</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Operasi vectorized, broadcasting, statistik, dan boolean filtering</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Kekuatan utama NumPy adalah <em>vectorized operation</em>, di mana seluruh operasi aritmatika dieksekusi serentak tanpa perlu menulis perulangan (loop) manual.
                        </p>

                        <div class="code-block my-4">
                            <div class="code-header">
                                <span>Python &bull; Vectorized & Filtering</span>
                            </div>
                            <pre><code><span class="keyword">import</span> numpy <span class="keyword">as</span> np

nilai = np.array([<span class="number">78</span>, <span class="number">85</span>, <span class="number">92</span>, <span class="number">67</span>, <span class="number">90</span>, <span class="number">73</span>])

<span class="comment"># Operasi agregasi</span>
<span class="function">print</span>(<span class="string">"Rata-rata :"</span>, np.mean(nilai))
<span class="function">print</span>(<span class="string">"Tertinggi :"</span>, np.max(nilai))

<span class="comment"># Boolean indexing (filter nilai >= 75)</span>
lulus = nilai[nilai >= <span class="number">75</span>]
<span class="function">print</span>(<span class="string">"Nilai Lulus:"</span>, lulus)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Rata-rata : 80.83333333333333<br>Tertinggi : 92<br>Nilai Lulus: [78 85 92 90]</span>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 4: Series Pandas ========== --}}
                    <article id="materi-4" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">4</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Series Pandas</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Struktur data 1 dimensi berlabel mirip kolom spreadsheet</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            <strong>Pandas Series</strong> adalah array satu dimensi yang dilengkapi dengan indeks atau label kustom pada setiap baris datanya.
                        </p>

                        <div class="code-block my-4">
                            <div class="code-header">
                                <span>Python &bull; Membuat & Mengakses Series</span>
                            </div>
                            <pre><code><span class="keyword">import</span> pandas <span class="keyword">as</span> pd

nilai = pd.Series([<span class="number">85</span>, <span class="number">92</span>, <span class="number">78</span>], index=[<span class="string">"Adelia"</span>, <span class="string">"Budi"</span>, <span class="string">"Dinda"</span>])
<span class="function">print</span>(nilai)
<span class="function">print</span>(<span class="string">"\nNilai Budi:"</span>, nilai[<span class="string">"Budi"</span>])</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Adelia    85<br>Budi      92<br>Dinda     78<br>dtype: int64<br><br>Nilai Budi: 92</span>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 5: DataFrame Pandas ========== --}}
                    <article id="materi-5" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">5</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">DataFrame Pandas</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Struktur data tabel 2 dimensi dengan baris dan kolom berlabel</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            <strong>DataFrame</strong> adalah komponen paling populer di Pandas, menyerupai lembar kerja Excel atau tabel database SQL.
                        </p>

                        <div class="code-block my-4">
                            <div class="code-header">
                                <span>Python &bull; Pembuatan DataFrame</span>
                            </div>
                            <pre><code><span class="keyword">import</span> pandas <span class="keyword">as</span> pd

data = {
    <span class="string">"nama"</span>: [<span class="string">"Adelia"</span>, <span class="string">"Budi"</span>, <span class="string">"Dinda"</span>],
    <span class="string">"nilai"</span>: [<span class="number">85</span>, <span class="number">72</span>, <span class="number">91</span>],
    <span class="string">"lulus"</span>: [<span class="builtin">True</span>, <span class="builtin">False</span>, <span class="builtin">True</span>]
}

df = pd.DataFrame(data)
<span class="function">print</span>(df)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>     nama  nilai  lulus<br>0  Adelia     85   True<br>1    Budi     72  False<br>2   Dinda     91   True</span>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 6: Membaca & Memfilter Data ========== --}}
                    <article id="materi-6" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">6</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Membaca & Memfilter Data</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Membaca data file CSV, filtering kondisi ganda, dan agregasi groupby</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Gunakan <code>pd.read_csv()</code> untuk membaca berkas data eksternal, ekspresi boolean untuk filtering, dan <code>.groupby()</code> untuk meringkas statistik kelompok data mirip SQL.
                        </p>

                        <div class="code-block my-4">
                            <div class="code-header">
                                <span>Python &bull; Filtering & Groupby</span>
                            </div>
                            <pre><code><span class="keyword">import</span> pandas <span class="keyword">as</span> pd

df = pd.DataFrame({
    <span class="string">"nama"</span>: [<span class="string">"Adelia"</span>, <span class="string">"Budi"</span>, <span class="string">"Dinda"</span>, <span class="string">"Eko"</span>],
    <span class="string">"nilai"</span>: [<span class="number">85</span>, <span class="number">72</span>, <span class="number">91</span>, <span class="number">65</span>],
    <span class="string">"kelas"</span>: [<span class="string">"A"</span>, <span class="string">"B"</span>, <span class="string">"A"</span>, <span class="string">"B"</span>]
})

<span class="comment"># Rata-rata per kelas</span>
statistik = df.groupby(<span class="string">"kelas"</span>)[<span class="string">"nilai"</span>].mean()
<span class="function">print</span>(statistik)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>kelas<br>A    88.0<br>B    68.5<br>Name: nilai, dtype: float64</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 mt-6">
                            <h4 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                                <i data-lucide="key" class="w-4 h-4 text-yellow-600"></i> Rangkuman Modul 07
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-2 m-0 pl-4">
                                <li><strong>NumPy</strong>: array multidimensi cepat, operasi matematika tervektorisasi, broadcasting.</li>
                                <li><strong>Series</strong>: array satu dimensi dengan label indeks kustom.</li>
                                <li><strong>DataFrame</strong>: tabel dua dimensi berlabel baris dan kolom.</li>
                                <li><strong>Filtering & Groupby</strong>: alat inti untuk analisis eksplorasi data (EDA).</li>
                            </ul>
                        </div>
                    </article>

                    {{-- ========== NAVIGASI BAWAH ========== --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
                        <a href="{{ route('modul.detail.06') }}"
                           class="w-full sm:w-auto flex items-center justify-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700 bg-white border border-gray-200 px-5 py-2.5 rounded-xl transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Modul Sebelumnya (Modul 06)
                        </a>

                        @auth
                            <form action="{{ route('modul.toggle-complete', 7) }}" method="POST" class="w-full sm:w-auto">
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

                        <a href="{{ route('modul.index') }}"
                           class="w-full sm:w-auto flex items-center justify-center gap-2 text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 px-5 py-2.5 rounded-xl transition-colors">
                            Kembali ke Daftar Modul <i data-lucide="arrow-right" class="w-4 h-4"></i>
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
