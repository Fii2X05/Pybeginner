<x-layouts.app title="Belajar Python Interaktif & Gratis untuk Pemula" active="beranda" navbar="landing" :flush="true">

    {{-- ===================== HERO ===================== --}}
    <section id="beranda" class="relative scroll-mt-24 overflow-hidden bg-emerald-50">
        <img src="{{ asset('images/hero-illustration.png') }}" alt=""
             class="absolute inset-0 z-0 h-full w-full object-cover object-left">

        <div class="relative z-10 mx-auto grid max-w-7xl items-center gap-12 px-6 py-16 lg:grid-cols-5 lg:px-10 lg:py-24">

    {{-- Ikon: pakai Lucide (lihat instruksi instalasi di bagian bawah file) --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .font-mono-code { font-family: ui-monospace, 'Fira Code', 'Courier New', monospace; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- ===================== NAVBAR ===================== --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-gray-100">
        <nav class="max-w-7xl mx-auto px-6 lg:px-10 h-20 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-9 w-auto">
            </a>

            {{-- Menu Tengah: Anchor ke section bawah --}}
            <ul class="hidden md:flex items-center gap-8 text-sm font-medium">
                <li>
                    <a href="#beranda" class="nav-link text-gray-900 border-b-2 border-emerald-600 pb-1 hover:text-emerald-600 transition-all">
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="#fitur" class="nav-link text-gray-600 hover:text-emerald-600 transition-all">
                        Fitur Unggulan
                    </a>
                </li>
                <li>
                    <a href="#metode" class="nav-link text-gray-600 hover:text-emerald-600 transition-all">
                        Metode Belajar
                    </a>
                </li>
                <li>
                    <a href="#mulai-coding" class="nav-link text-gray-600 hover:text-emerald-600 transition-all">
                        Mulai Coding
                    </a>
                </li>
            </ul>

            {{-- Aksi Kanan --}}
            <div class="flex items-center gap-4">
                @auth
                    <div class="flex items-center gap-3">
                        <span class="hidden sm:inline text-sm font-medium text-gray-700">
                            Hai, <strong class="text-emerald-700">{{ Auth::user()->name }}</strong>
                        </span>
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover border border-emerald-500">
                        @else
                            <a href="{{ route('profile') }}"
                               class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center text-white font-semibold text-xs shadow">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                    title="Keluar"
                                    class="text-xs bg-gray-100 hover:bg-red-50 text-gray-600 hover:text-red-600 px-3 py-2 rounded-lg font-medium transition-colors flex items-center gap-1.5">
                                <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                <span class="hidden sm:inline">Keluar</span>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline text-sm font-medium text-gray-700 hover:text-gray-900">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 text-sm font-semibold px-4 py-2 rounded-full transition-colors">
                        Daftar Gratis
                    </a>
                @endauth

                {{-- Tombol menu mobile --}}
                <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
                        class="md:hidden text-gray-700">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </nav>

        {{-- Flash message --}}
        @if (session('status'))
            <div class="bg-emerald-600 text-white text-sm text-center py-2.5 px-4 flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        {{-- Menu Mobile --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 px-6 py-4 space-y-3 text-sm font-medium text-gray-700">
            <a href="#beranda" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block hover:text-emerald-600">Beranda</a>
            <a href="#fitur" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block hover:text-emerald-600">Fitur Unggulan</a>
            <a href="#metode" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block hover:text-emerald-600">Metode Belajar</a>
            <a href="#mulai-coding" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block hover:text-emerald-600">Mulai Coding</a>
            <div class="pt-3 border-t border-gray-100 flex items-center gap-3">
                @auth
                    <span class="text-xs font-semibold text-gray-700">Hai, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-3 py-1.5 rounded-full">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 text-xs font-semibold px-3 py-1.5 rounded-full">Daftar Gratis</a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ===================== HERO SECTION ===================== --}}
    <section id="beranda" class="relative overflow-hidden scroll-mt-24">
        {{-- Background hero: 1 gambar utuh berisi ilustrasi maskot + efek blur, sesuai desain asli --}}
        <img src="{{ asset('images/hero-illustration.png') }}"
             alt=""
             class="absolute inset-0 w-full h-full object-cover object-left z-0">

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 py-16 lg:py-24 grid lg:grid-cols-5 gap-12 items-center">

            {{-- Kolom Kiri: Teks --}}
            <div class="relative z-10 lg:col-span-3">
                <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 text-xs font-semibold px-4 py-2 rounded-full mb-6">
                    <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                    Belajar Python Interaktif & Gratis untuk Pemula
                </span>

                <h1 class="mb-6 text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl">
                    Belajar <span class="text-emerald-600">Python</span>. <span class="whitespace-nowrap">Tulis Kode.</span>
                    <br>
                    Dapatkan <span class="whitespace-nowrap rounded bg-yellow-300 px-2">Feedback Instan</span>.
                </h1>

                <p class="mb-8 max-w-md text-lg text-slate-600">
                    Pelajari Python dari nol langsung melalui browser tanpa perlu instalasi software atau konfigurasi environment yang rumit.
                </p>

                <div class="flex flex-col gap-4 sm:flex-row">
                    <a href="{{ route('modul.mulai') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3.5 font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                        Mulai Belajar Sekarang
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </a>
                    <a href="{{ route('modul.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-6 py-3.5 font-semibold text-slate-800 shadow-sm ring-1 ring-emerald-100 transition hover:ring-emerald-300">
                        Lihat Modul Pembelajaran
                    </a>
                </div>
            </div>

            {{-- Mockup editor (gaya sama dengan editor di halaman latihan) --}}
            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-3xl bg-slate-900 shadow-2xl">
                    <div class="flex items-center justify-between px-5 py-3 text-xs text-slate-300">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-yellow-300"></span>
                            <span class="ml-2 font-mono">main.py</span>
                        </div>
                        <span>Python 3</span>
                    </div>

                    <div class="overflow-x-auto px-5 py-4 font-mono text-sm leading-relaxed text-slate-200">
<pre><span class="text-slate-500"># Program sapaan ramah PyBeginner</span>
<span class="text-emerald-300">nama</span> = <span class="text-yellow-300">"Sukma"</span>
<span class="text-emerald-300">target</span> = <span class="text-yellow-300">"Memahami Dasar Python untuk Pemula"</span>

<span class="text-emerald-400">def</span> <span class="text-yellow-200">sambut</span>(user):
    <span class="text-emerald-400">return</span> <span class="text-yellow-300">f"Selamat datang di PyBeginner, {user}!"</span>

<span class="text-yellow-200">print</span>(sambut(nama))</pre>
                    </div>

                    <div class="flex items-center justify-between bg-slate-800 px-5 py-3">
                        <span class="flex items-center gap-1.5 text-xs text-slate-400">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span> Siap dijalankan
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-xl bg-yellow-400 px-3 py-1.5 text-xs font-semibold text-slate-900">
                            Jalankan <i data-lucide="play" class="h-3 w-3"></i>
                        </span>
                    </div>

                    <div class="border-t border-slate-700 px-5 py-4 font-mono text-xs">
                        <div class="mb-2 flex items-center justify-between text-slate-500">
                            <span>OUTPUT</span>
                            <span class="text-emerald-400">Lolos: 1/1</span>
                        </div>
                        <p class="text-slate-300">&gt; Selamat datang di PyBeginner, Sukma!</p>
                        <p class="mt-2 inline-block rounded-lg bg-emerald-900/40 px-2 py-1 text-emerald-300">
                            ✓ Test case 1 lolos dalam 0.04s
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== FITUR UNGGULAN ===================== --}}
    <section id="fitur" class="bg-emerald-50/70 py-20 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-4 py-1.5 rounded-full mb-4">
                    Fitur Unggulan
                </span>
                <h2 class="mb-4 text-3xl font-extrabold text-slate-900 sm:text-4xl">
                    Dirancang Khusus untuk Memudahkan Pemula
                </h2>
                <p class="text-slate-600">
                    Semua rintangan teknis yang biasa ditemui saat pertama kali belajar coding kami singkirkan agar kamu bisa fokus memahami logika pemrograman.
                </p>
            </div>

            @php
                $fitur = [
                    ['icon' => 'monitor',        'title' => 'Belajar Langsung dari Browser', 'desc' => 'Tanpa instalasi IDE atau compiler. Cukup buka browser favorit di laptop spek standar maupun Chromebook tanpa lag.'],
                    ['icon' => 'graduation-cap', 'title' => 'Materi Python dari Nol',        'desc' => 'Disusun bertahap dari konsep variabel hingga manipulasi data terapan dengan bahasa santai yang tidak intimidatif bagi pemula.'],
                    ['icon' => 'square-code',    'title' => 'Latihan Coding Interaktif',     'desc' => 'Tulis dan modifikasi kode langsung di web-editor dengan petunjuk soal terstruktur serta contoh uji coba.'],
                    ['icon' => 'shield-check',   'title' => 'Penilaian Kode Otomatis',       'desc' => 'Sistem auto-grading instan mengevaluasi ketepatan logikamu dengan skenario uji tersembunyi secara adil dan objektif.'],
                    ['icon' => 'message-square', 'title' => 'Feedback Instan & Ramah',       'desc' => 'Kalau jawabanmu belum tepat, materinya langsung ditampilkan supaya kamu bisa memahaminya lalu mencoba lagi.'],
                    ['icon' => 'line-chart',     'title' => 'Pantau Progress Belajar',       'desc' => 'Pantau modul yang sudah kamu tuntaskan dan arsip riwayat pengerjaan tugas di satu tempat.'],
                ];
            @endphp

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($fitur as $i => $item)
                    @php
                        $kuning = $i % 2 === 1;
                    @endphp
                    <div class="reveal reveal-delay-{{ $i % 3 + 1 }} rounded-3xl bg-white p-6 shadow-sm ring-1 ring-emerald-100 transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:ring-emerald-300">
                        <div class="mb-5 flex h-11 w-11 items-center justify-center rounded-xl {{ $kuning ? 'bg-yellow-100 text-yellow-600' : 'bg-emerald-100 text-emerald-700' }}">
                            <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5"></i>
                        </div>
                        <h3 class="mb-2 font-bold text-slate-900">{{ $item['title'] }}</h3>
                        <p class="text-sm leading-relaxed text-slate-600">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== METODE PEMBELAJARAN ===================== --}}
    <section id="metode" class="py-20 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="inline-block bg-yellow-100 text-yellow-700 text-xs font-semibold px-4 py-1.5 rounded-full mb-4">
                    Metode Pembelajaran
                </span>
                <h2 class="mb-4 text-3xl font-extrabold text-slate-900 sm:text-4xl">
                    3 Langkah Mudah Menguasai Python
                </h2>
                <p class="text-slate-600">
                    Alur ringkas yang memastikan kamu tidak hanya membaca teori, tetapi langsung membangun refleks coding.
                </p>
            </div>

            @php
                $langkah = [
                    ['no' => 1, 'title' => 'Kerjakan Soal',          'desc' => 'Pilih tingkat kesulitan, baca soalnya, lalu langsung tulis kodemu di editor tanpa harus membaca materi dulu.'],
                    ['no' => 2, 'title' => 'Kirim & Dinilai',         'desc' => 'Kirim kodemu dan dapatkan skor instan dari penguji otomatis beserta hasil tiap test case.'],
                    ['no' => 3, 'title' => 'Pelajari, Lalu Coba Lagi', 'desc' => 'Kalau belum tepat, materi yang relevan ditampilkan. Pahami, perbaiki kodemu, dan kirim ulang sampai nilainya sempurna.'],
                ];
            @endphp

            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($langkah as $step)
                    <div class="reveal reveal-delay-{{ $loop->iteration }} rounded-3xl bg-white p-6 shadow-sm ring-1 ring-emerald-100">
                        <div class="mb-5 flex h-10 w-10 items-center justify-center rounded-xl font-bold {{ $loop->iteration === 2 ? 'bg-yellow-100 text-yellow-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $step['no'] }}
                        </div>
                        <h3 class="mb-2 font-bold text-slate-900">{{ $step['title'] }}</h3>
                        <p class="text-sm leading-relaxed text-slate-600">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== CTA BANNER ===================== --}}
    <section id="mulai-coding" class="pb-20 scroll-mt-24">
        <div class="max-w-6xl mx-auto px-6 lg:px-10">
            <div class="reveal relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-800 to-emerald-600 px-8 py-16 sm:px-16 text-center">
                <span class="inline-flex items-center gap-2 bg-white/15 text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-6">
                    <i data-lucide="rocket" class="w-3.5 h-3.5"></i>
                    Akses 100% Gratis Selamanya
                </span>

                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">
                    Siap Menulis Kode Python Pertamamu Hari Ini?
                </h2>
                <p class="text-emerald-100 max-w-xl mx-auto mb-8">
                    Bergabunglah dengan ribuan pemula lainnya. Buka modul pertamamu sekarang dan buktikan bahwa coding itu mudah dan menyenangkan.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-6 py-3.5 rounded-xl transition-colors">
                        Mulai Belajar Gratis <i data-lucide="rocket" class="w-4 h-4"></i>
                    </a>
                    <a href="{{ route('playground') }}"
                       class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-semibold px-6 py-3.5 rounded-xl transition-colors">
                        Coba Mulai Coding
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="border-t border-gray-100 py-14">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="bot" class="w-4 h-4 text-emerald-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>