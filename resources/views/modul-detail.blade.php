<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modul 01: Dasar Python & Sintaks Awal - PyBeginner</title>

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
                <span class="text-gray-900 font-semibold">Modul 01</span>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">Pemula</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                            <i data-lucide="check" class="w-3 h-3"></i> Selesai
                        </span>
                        <span class="text-xs text-gray-400 font-mono-code">MODUL 01</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-3">Dasar Python & Sintaks Awal</h1>
                    <p class="text-gray-600 max-w-2xl text-sm leading-relaxed">
                        Mengenal struktur program Python, fungsi <code>print()</code>, komentar kode, dan aturan indentasi yang menjadi ciri khas Python.
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
                                        <p class="font-semibold text-gray-900 text-sm">Apa itu Python?</p>
                                        <p class="text-[11px] text-gray-400">Pengenalan bahasa Python</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-2" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Program Pertamamu</p>
                                        <p class="text-[11px] text-gray-400">Fungsi print() dan output</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-3" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Input dari Pengguna</p>
                                        <p class="text-[11px] text-gray-400">Fungsi input() interaktif</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-4" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Komentar Kode</p>
                                        <p class="text-[11px] text-gray-400">Dokumentasi dalam kode</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-5" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Aturan Indentasi</p>
                                        <p class="text-[11px] text-gray-400">Struktur blok kode Python</p>
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

                    {{-- ========== MATERI 1: Apa itu Python? ========== --}}
                    <article id="materi-1" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">1</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Apa itu Python?</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Pengenalan bahasa pemrograman Python</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            <strong>Python</strong> adalah bahasa pemrograman tingkat tinggi yang diciptakan oleh <strong>Guido van Rossum</strong> dan pertama kali dirilis pada tahun <strong>1991</strong>. Python dirancang dengan filosofi utama: <em>keterbacaan kode</em> — artinya kode Python mudah dibaca dan dipahami, bahkan oleh pemula sekalipun.
                        </p>

                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 my-6">
                            <div class="flex items-start gap-3">
                                <i data-lucide="lightbulb" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-emerald-800 text-sm mb-1">Tahukah kamu?</p>
                                    <p class="text-emerald-700 text-sm leading-relaxed m-0">Nama "Python" bukan berasal dari ular, melainkan dari acara komedi BBC berjudul <em>Monty Python's Flying Circus</em> yang disukai oleh Guido van Rossum.</p>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Kenapa Belajar Python?</h3>

                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="smile" class="w-4 h-4 text-blue-600"></i>
                                    <span class="font-semibold text-sm text-gray-900">Mudah Dipelajari</span>
                                </div>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">Sintaks Python sangat mirip dengan bahasa Inggris sehari-hari, membuatnya ideal untuk pemula.</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="layers" class="w-4 h-4 text-purple-600"></i>
                                    <span class="font-semibold text-sm text-gray-900">Serbaguna</span>
                                </div>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">Bisa dipakai untuk web, data science, AI, otomasi, scripting, dan banyak lagi.</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="users" class="w-4 h-4 text-emerald-600"></i>
                                    <span class="font-semibold text-sm text-gray-900">Komunitas Besar</span>
                                </div>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">Jutaan pengembang di seluruh dunia, ribuan library open-source, dan dokumentasi lengkap.</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="trending-up" class="w-4 h-4 text-yellow-600"></i>
                                    <span class="font-semibold text-sm text-gray-900">Sangat Diminati</span>
                                </div>
                                <p class="text-xs text-gray-600 m-0 leading-relaxed">Konsisten menjadi bahasa pemrograman paling populer versi indeks TIOBE sejak 2021.</p>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Perbandingan Sintaks</h3>
                        <p class="text-sm text-gray-600 mb-4">Lihat betapa ringkasnya Python dibandingkan bahasa lain untuk mencetak "Hello, World!":</p>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Python</p>
                                <div class="code-block">
                                    <div class="code-header"><span>hello.py</span></div>
                                    <pre><code><span class="function">print</span>(<span class="string">"Hello, World!"</span>)</code></pre>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Java</p>
                                <div class="code-block">
                                    <div class="code-header"><span>Hello.java</span></div>
                                    <pre><code><span class="keyword">public class</span> Hello {
    <span class="keyword">public static void</span> main(String[] args) {
        System.out.<span class="function">println</span>(<span class="string">"Hello, World!"</span>);
    }
}</code></pre>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mt-6">
                            <div class="flex items-start gap-3">
                                <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"></i>
                                <p class="text-blue-800 text-sm leading-relaxed m-0"><strong>Kesimpulan:</strong> Python hanya butuh <strong>1 baris</strong> kode, sementara Java membutuhkan <strong>5 baris</strong> untuk menghasilkan output yang sama. Itulah kenapa Python sangat populer di kalangan pemula!</p>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 2: Program Pertamamu ========== --}}
                    <article id="materi-2" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">2</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Program Pertamamu</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Menulis dan menjalankan kode Python dengan print()</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Fungsi <code>print()</code> adalah fungsi bawaan Python yang digunakan untuk menampilkan teks atau nilai ke layar (console). Ini adalah perintah pertama yang wajib kamu kuasai!
                        </p>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Sintaks Dasar print()</h3>

                        <div class="code-block mb-4">
                            <div class="code-header">
                                <span>contoh_print.py</span>
                                <button class="text-gray-400 hover:text-white transition-colors text-xs flex items-center gap-1" onclick="navigator.clipboard.writeText(this.closest('.code-block').querySelector('code').textContent)">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin
                                </button>
                            </div>
                            <pre><code><span class="comment"># Mencetak teks sederhana</span>
<span class="function">print</span>(<span class="string">"Hello, World!"</span>)
<span class="function">print</span>(<span class="string">"Selamat datang di PyBeginner!"</span>)

<span class="comment"># Mencetak angka</span>
<span class="function">print</span>(<span class="number">42</span>)
<span class="function">print</span>(<span class="number">3.14</span>)

<span class="comment"># Mencetak beberapa nilai sekaligus</span>
<span class="function">print</span>(<span class="string">"Nama saya"</span>, <span class="string">"Python"</span>, <span class="string">"versi"</span>, <span class="number">3</span>)

<span class="comment"># Menggabungkan teks (concatenation)</span>
<span class="function">print</span>(<span class="string">"Halo, "</span> + <span class="string">"Dunia!"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Hello, World!<br>Selamat datang di PyBeginner!<br>42<br>3.14<br>Nama saya Python versi 3<br>Halo, Dunia!</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Parameter Penting print()</h3>

                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Parameter</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Fungsi</th>
                                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-b border-gray-200">Contoh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-3 border-b border-gray-100"><code>sep</code></td>
                                        <td class="px-4 py-3 text-gray-600 border-b border-gray-100">Pemisah antar nilai</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">print("A", "B", sep="-") → A-B</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 border-b border-gray-100"><code>end</code></td>
                                        <td class="px-4 py-3 text-gray-600 border-b border-gray-100">Karakter akhir baris</td>
                                        <td class="px-4 py-3 font-mono-code text-xs border-b border-gray-100">print("Halo", end="!") → Halo!</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="code-block">
                            <div class="code-header"><span>parameter_print.py</span></div>
                            <pre><code><span class="comment"># Menggunakan parameter sep</span>
<span class="function">print</span>(<span class="string">"2024"</span>, <span class="string">"10"</span>, <span class="string">"07"</span>, sep=<span class="string">"-"</span>)

<span class="comment"># Menggunakan parameter end</span>
<span class="function">print</span>(<span class="string">"Halo"</span>, end=<span class="string">" "</span>)
<span class="function">print</span>(<span class="string">"Dunia!"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>2024-10-07<br>Halo Dunia!</span>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 3: Input dari Pengguna ========== --}}
                    <article id="materi-3" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">3</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Input dari Pengguna</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Menerima masukan dengan fungsi input()</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Fungsi <code>input()</code> digunakan untuk meminta pengguna memasukkan data melalui keyboard. Data yang diterima oleh <code>input()</code> <strong>selalu bertipe string</strong>, meskipun pengguna mengetikkan angka.
                        </p>

                        <div class="code-block mb-6">
                            <div class="code-header"><span>contoh_input.py</span></div>
                            <pre><code><span class="comment"># Meminta input nama</span>
nama = <span class="function">input</span>(<span class="string">"Siapa namamu? "</span>)
<span class="function">print</span>(<span class="string">"Halo,"</span>, nama, <span class="string">"! Selamat belajar Python!"</span>)

<span class="comment"># Meminta input umur (perlu dikonversi ke int)</span>
umur_str = <span class="function">input</span>(<span class="string">"Berapa umurmu? "</span>)
umur = <span class="function">int</span>(umur_str)
<span class="function">print</span>(<span class="string">"Tahun depan umurmu"</span>, umur + <span class="number">1</span>, <span class="string">"tahun."</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT (contoh):</p>
                                <span>Siapa namamu? <em style="color:#94a3b8">Adelia</em><br>Halo, Adelia ! Selamat belajar Python!<br>Berapa umurmu? <em style="color:#94a3b8">20</em><br>Tahun depan umurmu 21 tahun.</span>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-6">
                            <div class="flex items-start gap-3">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="font-semibold text-yellow-800 text-sm mb-1">Perhatian!</p>
                                    <p class="text-yellow-700 text-sm leading-relaxed m-0">Hasil dari <code>input()</code> <strong>selalu bertipe string</strong>. Jika ingin melakukan operasi matematika, kamu harus mengkonversinya terlebih dahulu dengan <code>int()</code> atau <code>float()</code>.</p>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">f-string: Cara Modern Format Teks</h3>

                        <p class="text-gray-700 leading-relaxed">
                            Sejak Python 3.6, kamu bisa menggunakan <strong>f-string</strong> untuk menyisipkan variabel langsung ke dalam string dengan lebih rapi:
                        </p>

                        <div class="code-block">
                            <div class="code-header"><span>fstring.py</span></div>
                            <pre><code>nama = <span class="function">input</span>(<span class="string">"Nama: "</span>)
umur = <span class="function">int</span>(<span class="function">input</span>(<span class="string">"Umur: "</span>))

<span class="comment"># f-string — sisipkan variabel ke dalam { }</span>
<span class="function">print</span>(<span class="string">f"Halo, {nama}! Umurmu {umur} tahun."</span>)
<span class="function">print</span>(<span class="string">f"Tahun depan kamu berumur {umur + 1} tahun."</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT (contoh):</p>
                                <span>Nama: <em style="color:#94a3b8">Adelia</em><br>Umur: <em style="color:#94a3b8">20</em><br>Halo, Adelia! Umurmu 20 tahun.<br>Tahun depan kamu berumur 21 tahun.</span>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 4: Komentar Kode ========== --}}
                    <article id="materi-4" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">4</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Komentar Kode</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Mendokumentasikan kode agar mudah dipahami</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            <strong>Komentar</strong> adalah bagian kode yang <em>tidak dieksekusi</em> oleh Python. Komentar digunakan untuk menjelaskan apa yang dilakukan kode, membuat catatan untuk diri sendiri atau tim, dan meningkatkan keterbacaan program.
                        </p>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Jenis Komentar</h3>

                        <div class="code-block mb-6">
                            <div class="code-header"><span>komentar.py</span></div>
                            <pre><code><span class="comment"># Ini adalah komentar satu baris</span>
<span class="comment"># Python akan mengabaikan baris ini</span>

<span class="function">print</span>(<span class="string">"Halo!"</span>)  <span class="comment"># komentar di samping kode (inline)</span>

<span class="string">"""
Ini adalah komentar multi-baris (docstring).
Biasa digunakan untuk menjelaskan fungsi,
kelas, atau modul secara detail.
"""</span>

<span class="comment"># Contoh penggunaan komentar yang baik:</span>
harga_satuan = <span class="number">15000</span>   <span class="comment"># harga per item dalam rupiah</span>
jumlah = <span class="number">3</span>              <span class="comment"># jumlah item yang dibeli</span>
total = harga_satuan * jumlah
<span class="function">print</span>(<span class="string">f"Total: Rp{total}"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Halo!<br>Total: Rp45000</span>
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                                <p class="font-semibold text-emerald-800 text-sm mb-2 flex items-center gap-2"><i data-lucide="check-circle" class="w-4 h-4"></i> Komentar yang Baik</p>
                                <ul class="text-xs text-emerald-700 space-y-1 m-0 pl-4">
                                    <li>Menjelaskan <em>mengapa</em> kode ditulis</li>
                                    <li>Ringkas dan informatif</li>
                                    <li>Diperbarui saat kode berubah</li>
                                </ul>
                            </div>
                            <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                                <p class="font-semibold text-red-800 text-sm mb-2 flex items-center gap-2"><i data-lucide="x-circle" class="w-4 h-4"></i> Komentar yang Buruk</p>
                                <ul class="text-xs text-red-700 space-y-1 m-0 pl-4">
                                    <li>Menyatakan hal yang sudah jelas</li>
                                    <li>Terlalu panjang dan bertele-tele</li>
                                    <li>Sudah tidak relevan dengan kode</li>
                                </ul>
                            </div>
                        </div>
                    </article>

                    {{-- ========== MATERI 5: Aturan Indentasi ========== --}}
                    <article id="materi-5" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">5</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Aturan Indentasi</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Struktur blok kode khas Python</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Tidak seperti bahasa lain yang menggunakan kurung kurawal <code>{ }</code>, Python menggunakan <strong>indentasi</strong> (spasi di awal baris) untuk menentukan blok kode. Ini membuat kode Python terlihat rapi dan terstruktur secara default.
                        </p>

                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 my-6">
                            <div class="flex items-start gap-3">
                                <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"></i>
                                <p class="text-blue-800 text-sm leading-relaxed m-0"><strong>Standar Python:</strong> Gunakan <strong>4 spasi</strong> untuk setiap tingkat indentasi. Jangan campurkan spasi dan tab dalam satu file!</p>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Contoh Indentasi yang Benar</h3>

                        <div class="code-block mb-6">
                            <div class="code-header"><span>indentasi_benar.py</span></div>
                            <pre><code>nilai = <span class="number">85</span>

<span class="keyword">if</span> nilai >= <span class="number">80</span>:
    <span class="function">print</span>(<span class="string">"Selamat! Kamu mendapat nilai A"</span>)  <span class="comment"># ← indentasi 4 spasi</span>
    <span class="function">print</span>(<span class="string">"Pertahankan prestasimu!"</span>)          <span class="comment"># ← level yang sama</span>
<span class="keyword">else</span>:
    <span class="function">print</span>(<span class="string">"Terus semangat belajar ya!"</span>)

<span class="function">print</span>(<span class="string">"Program selesai."</span>)  <span class="comment"># ← di luar blok if/else</span></code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Selamat! Kamu mendapat nilai A<br>Pertahankan prestasimu!<br>Program selesai.</span>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Contoh Indentasi yang Salah</h3>

                        <div class="code-block mb-6" style="border: 2px solid #fca5a5;">
                            <div class="code-header" style="background: #7f1d1d;">
                                <span style="color: #fca5a5;">❌ indentasi_salah.py — AKAN ERROR</span>
                            </div>
                            <pre><code>nilai = <span class="number">85</span>

<span class="keyword">if</span> nilai >= <span class="number">80</span>:
<span class="function">print</span>(<span class="string">"Ini akan error!"</span>)  <span class="comment"># ← tidak ada indentasi!</span>
<span class="comment"># IndentationError: expected an indented block</span></code></pre>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                            <h4 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2"><i data-lucide="key" class="w-4 h-4 text-yellow-600"></i> Poin Penting Indentasi</h4>
                            <ul class="text-sm text-gray-700 space-y-2 m-0 pl-4">
                                <li>Selalu gunakan <strong>4 spasi</strong> per level (standar PEP 8)</li>
                                <li>Blok kode setelah <code>if</code>, <code>for</code>, <code>while</code>, <code>def</code>, <code>class</code> <strong>wajib</strong> diindentasi</li>
                                <li>Semua baris dalam satu blok harus memiliki jumlah spasi yang <strong>sama</strong></li>
                                <li>Jangan mencampur <strong>spasi</strong> dan <strong>tab</strong> — pilih salah satu (spasi direkomendasikan)</li>
                            </ul>
                        </div>
                    </article>

                    {{-- ========== NAVIGASI BAWAH ========== --}}
                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('modul.index') }}"
                           class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700 bg-white border border-gray-200 px-5 py-2.5 rounded-xl transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Modul
                        </a>
                        <a href="#"
                           class="flex items-center gap-2 text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 px-5 py-2.5 rounded-xl transition-colors">
                            Modul Selanjutnya <i data-lucide="arrow-right" class="w-4 h-4"></i>
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
