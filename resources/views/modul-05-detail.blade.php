<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modul 05: Fungsi & Modularitas Kode - PyBeginner</title>

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
                <span class="text-gray-900 font-semibold">Modul 05</span>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-100 text-purple-700">Menengah</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                            <i data-lucide="check" class="w-3 h-3"></i> Siap Dipelajari
                        </span>
                        <span class="text-xs text-gray-400 font-mono-code">MODUL 05</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-3">Fungsi & Modularitas Kode</h1>
                    <p class="text-gray-600 max-w-2xl text-sm leading-relaxed">
                        Membuat kode yang rapi, terstruktur, dan dapat digunakan kembali menggunakan fungsi <code>def</code>, parameter, argumen default, nilai kembalian (<code>return</code>), serta menguasai scope variabel lokal dan global.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="text-right mr-3">
                        <p class="text-xs text-gray-500 mb-1">Total Materi</p>
                        <p class="text-lg font-extrabold text-emerald-700">3/3 Materi</p>
                    </div>
                    <div class="w-14 h-14 rounded-full border-4 border-emerald-500 flex items-center justify-center">
                        <span class="text-sm font-extrabold text-emerald-700">100%</span>
                    </div>
                </div>
            </div>

            {{-- Stat mini --}}
            <div class="flex items-center gap-6 mt-6 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> 3 Materi</span>
                <span class="flex items-center gap-1.5"><i data-lucide="code" class="w-3.5 h-3.5"></i> Interaktif</span>
                <span class="flex items-center gap-1.5"><i data-lucide="clock" class="w-3.5 h-3.5"></i> ±30 Menit</span>
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
                            <p class="text-xs text-gray-400 mt-0.5">3 materi dalam modul ini</p>
                        </div>
                        <nav class="py-2">
                            <a href="#materi-1" class="sidebar-link active block px-5 py-3 text-sm transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">Membuat Fungsi (def)</p>
                                        <p class="text-[11px] text-gray-400">Parameter & argumen</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-2" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Return & Default</p>
                                        <p class="text-[11px] text-gray-400">Return value & default arg</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#materi-3" class="sidebar-link block px-5 py-3 text-sm text-gray-600 transition-all">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </span>
                                    <div>
                                        <p class="font-medium text-sm">Scope Variabel</p>
                                        <p class="text-[11px] text-gray-400">Lokal vs global</p>
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

                    {{-- ========== MATERI 1: Membuat Fungsi ========== --}}
                    <article id="materi-1" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">1</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Membuat Fungsi dengan def</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Membuat potongan kode bernama yang dapat dipakai berulang kali</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Fungsi adalah blok kode terorganisir dan dapat digunakan kembali (<em>reusable</em>) untuk menjalankan satu tindakan spesifik. Di Python, fungsi didefinisikan menggunakan kata kunci <code>def</code> diikuti dengan nama fungsi dan tanda kurung <code>()</code>.
                        </p>

                        <div class="code-block my-4">
                            <div class="code-header">
                                <span>Python &bull; Definisi Fungsi Sederhana</span>
                            </div>
                            <pre><code><span class="keyword">def</span> <span class="function">sapa</span>(nama):
    <span class="function">print</span>(<span class="string">"Halo,"</span>, nama)

<span class="comment"># Memanggil fungsi</span>
sapa(<span class="string">"Dinda"</span>)
sapa(<span class="string">"Adelia"</span>)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Halo, Dinda<br>Halo, Adelia</span>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Variabel di dalam tanda kurung saat mendefinisikan fungsi (seperti <code>nama</code>) disebut <strong>parameter</strong>. Nilai nyata yang dikirimkan saat fungsi dipanggil (seperti <code>"Dinda"</code>) disebut <strong>argumen</strong>.
                        </p>
                    </article>

                    {{-- ========== MATERI 2: Return Value & Argumen Default ========== --}}
                    <article id="materi-2" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">2</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Return Value dan Argumen Default</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Mengembalikan hasil komputasi dan memberikan nilai bawaan pada parameter</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Gunakan pernyataan <code>return</code> untuk mengembalikan nilai keluaran dari suatu fungsi agar dapat disimpan ke variabel atau diproses lebih lanjut:
                        </p>

                        <div class="code-block my-4">
                            <div class="code-header">
                                <span>Python &bull; Return Value & Default Argument</span>
                            </div>
                            <pre><code><span class="keyword">def</span> <span class="function">luas_persegi</span>(sisi):
    <span class="keyword">return</span> sisi * sisi

hasil = luas_persegi(<span class="number">4</span>)
<span class="function">print</span>(<span class="string">"Luas:"</span>, hasil)

<span class="comment"># Parameter dengan nilai bawaan (default)</span>
<span class="keyword">def</span> <span class="function">sapa_user</span>(nama, sapaan=<span class="string">"Halo"</span>):
    <span class="keyword">return</span> <span class="string">f"{sapaan}, {nama}!"</span>

<span class="function">print</span>(sapa_user(<span class="string">"Dinda"</span>))
<span class="function">print</span>(sapa_user(<span class="string">"Adelia"</span>, <span class="string">"Selamat pagi"</span>))</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Luas: 16<br>Halo, Dinda!<br>Selamat pagi, Adelia!</span>
                            </div>
                        </div>

                        <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl my-4 text-sm text-amber-900">
                            <strong>Aturan Default Argument:</strong> Parameter dengan nilai default harus selalu diletakkan <em>setelah</em> parameter tanpa nilai default.
                        </div>
                    </article>

                    {{-- ========== MATERI 3: Scope Variabel ========== --}}
                    <article id="materi-3" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 prose max-w-none">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">3</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-gray-900 m-0">Scope Lokal dan Global</h2>
                                <p class="text-xs text-gray-400 m-0 mt-0.5">Memahami ruang lingkup hidup variabel di dalam vs di luar fungsi</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed">
                            Variabel yang dibuat di dalam sebuah fungsi memiliki <strong>scope lokal</strong> dan hanya dapat diakses di dalam fungsi tersebut. Sedangkan variabel yang dibuat di luar fungsi memiliki <strong>scope global</strong> dan dapat diakses dari mana saja.
                        </p>

                        <div class="code-block my-4">
                            <div class="code-header">
                                <span>Python &bull; Local vs Global Scope</span>
                            </div>
                            <pre><code>pesan = <span class="string">"global"</span>

<span class="keyword">def</span> <span class="function">contoh</span>():
    pesan = <span class="string">"lokal"</span>
    <span class="function">print</span>(<span class="string">"Di dalam fungsi :"</span>, pesan)

contoh()
<span class="function">print</span>(<span class="string">"Di luar fungsi  :"</span>, pesan)</code></pre>
                            <div class="code-output">
                                <p class="text-xs text-gray-400 mb-1 font-semibold">OUTPUT:</p>
                                <span>Di dalam fungsi : lokal<br>Di luar fungsi  : global</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 mt-6">
                            <h4 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                                <i data-lucide="key" class="w-4 h-4 text-yellow-600"></i> Rangkuman Modul 05
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-2 m-0 pl-4">
                                <li>Gunakan <code>def nama_fungsi(parameter):</code> untuk mendefinisikan fungsi baru.</li>
                                <li><code>return</code> menghentikan eksekusi fungsi dan mengembalikan hasil ke pemanggil.</li>
                                <li>Argumen default memungkinkan pemanggilan fungsi lebih fleksibel tanpa wajib mengisi semua parameter.</li>
                                <li>Hindari ketergantungan pada variabel global; selalu lewatkan data via parameter dan kembalikan dengan <code>return</code>.</li>
                            </ul>
                        </div>
                    </article>

                    {{-- ========== NAVIGASI BAWAH ========== --}}
                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('modul.detail.04') }}"
                           class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700 bg-white border border-gray-200 px-5 py-2.5 rounded-xl transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Modul Sebelumnya (Modul 04)
                        </a>
                        <a href="{{ route('modul.detail.06') }}"
                           class="flex items-center gap-2 text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 px-5 py-2.5 rounded-xl transition-colors">
                            Modul Berikutnya (Modul 06) <i data-lucide="arrow-right" class="w-4 h-4"></i>
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
