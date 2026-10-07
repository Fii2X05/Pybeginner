<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PyLearn - Belajar Python Interaktif & Gratis untuk Pemula</title>

    {{-- Jika project belum pakai Vite/Tailwind build, aktifkan CDN ini sebagai fallback cepat --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                <a href="{{ route('login') }}" class="hidden sm:inline text-sm font-medium text-gray-700 hover:text-gray-900">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 text-sm font-semibold px-4 py-2 rounded-full transition-colors">
                    Daftar Gratis
                </a>
                <a href="{{ route('profile') }}"
                   class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center text-white">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </a>

                {{-- Tombol menu mobile --}}
                <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
                        class="md:hidden text-gray-700">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </nav>

        {{-- Menu Mobile --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 px-6 py-4 space-y-3 text-sm font-medium text-gray-700">
            <a href="#beranda" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block hover:text-emerald-600">Beranda</a>
            <a href="#fitur" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block hover:text-emerald-600">Fitur Unggulan</a>
            <a href="#metode" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block hover:text-emerald-600">Metode Belajar</a>
            <a href="#mulai-coding" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="block hover:text-emerald-600">Mulai Coding</a>
            <div class="pt-3 border-t border-gray-100 flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Masuk</a>
                <a href="{{ route('register') }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 text-xs font-semibold px-3 py-1.5 rounded-full">Daftar Gratis</a>
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

                <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight text-gray-900 mb-6">
                    Belajar <span class="text-emerald-600">Python</span>. <span class="whitespace-nowrap">Tulis Kode.</span>
                    <br>
                    Dapatkan <span class="bg-yellow-300 px-2 rounded whitespace-nowrap">Feedback Instan</span>.
                </h1>

                <p class="text-gray-600 text-lg mb-8 max-w-md">
                    Pelajari Python dari nol langsung melalui browser tanpa perlu instalasi software atau konfigurasi environment yang rumit.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('modul.mulai') }}"
                       class="inline-flex items-center justify-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold px-6 py-3.5 rounded-xl transition-colors">
                        Mulai Belajar Sekarang
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    <a href="{{ route('modul.index') }}"
                       class="inline-flex items-center justify-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-800 font-semibold px-6 py-3.5 rounded-xl transition-colors">
                        Lihat Modul Pembelajaran
                    </a>
                </div>
            </div>

            {{-- Kolom Kanan: Mockup Code Editor --}}
            <div class="relative lg:col-span-2">
                <div class="relative bg-gray-900 rounded-2xl shadow-2xl overflow-hidden">
                    {{-- Title bar --}}
                    <div class="flex items-center justify-between px-4 py-3 bg-gray-800/80">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                            <span class="w-3 h-3 rounded-full bg-green-500"></span>
                            <span class="ml-3 text-xs text-gray-400 flex items-center gap-1">
                                <i data-lucide="code" class="w-3.5 h-3.5"></i> main.py
                            </span>
                        </div>
                        <span class="text-xs text-emerald-400 flex items-center gap-1">
                            <i data-lucide="circle-dot" class="w-3 h-3"></i> Python 3.11
                        </span>
                    </div>

                    {{-- Code body --}}
                    <div class="px-5 py-4 text-sm font-mono-code leading-relaxed text-gray-200 overflow-x-auto">
<pre><span class="text-gray-500"># Program sapaan ramah PyLearn</span>
<span class="text-purple-400">nama</span> = <span class="text-orange-300">"Sukma"</span>
<span class="text-purple-400">target</span> = <span class="text-orange-300">"Memahami Dasar Python untuk Pemula"</span>

<span class="text-pink-400">def</span> <span class="text-blue-300">sambut</span>(user):
    <span class="text-pink-400">return</span> <span class="text-orange-300">f"Selamat datang di PyBeginner, {user}!"</span>

<span class="text-blue-300">print</span>(sambut(nama))</pre>
                    </div>

                    {{-- Run bar --}}
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-800/60 border-t border-gray-700">
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <i data-lucide="circle" class="w-2.5 h-2.5 text-emerald-400"></i> In-Browser REPL Engine Ready
                        </span>
                        <button type="button" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold px-3 py-1.5 rounded-md flex items-center gap-1">
                            Run <i data-lucide="play" class="w-3 h-3"></i>
                        </button>
                    </div>

                    {{-- Output console --}}
                    <div class="bg-black px-5 py-4 text-xs font-mono-code">
                        <div class="flex items-center justify-between text-gray-500 mb-2">
                            <span>OUTPUT / CONSOLE</span>
                            <span class="text-emerald-400">Pass: 1/1</span>
                        </div>
                        <p class="text-gray-300">&gt; Selamat datang di PyBeginner, Sukma!</p>
                        <p class="mt-2 inline-block bg-emerald-900/40 text-emerald-400 px-2 py-1 rounded">
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
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">
                    Dirancang Khusus untuk Memudahkan Pemula
                </h2>
                <p class="text-gray-600">
                    Semua rintangan teknis yang biasa ditemui saat pertama kali belajar coding kami singkirkan agar kamu bisa fokus memahami logika pemrograman.
                </p>
            </div>

            @php
                $fitur = [
                    ['icon' => 'monitor', 'bg' => 'bg-blue-100', 'color' => 'text-blue-600', 'title' => 'Belajar Langsung dari Browser', 'desc' => 'Tanpa instalasi IDE atau compiler. Cukup buka browser favorit di laptop spek standar maupun Chromebook tanpa lag.'],
                    ['icon' => 'graduation-cap', 'bg' => 'bg-yellow-100', 'color' => 'text-yellow-600', 'title' => 'Materi Python dari Nol', 'desc' => 'Disusun bertahap dari konsep variabel hingga manipulasi data terapan dengan bahasa santai yang tidak intimidatif bagi pemula.'],
                    ['icon' => 'square-code', 'bg' => 'bg-indigo-100', 'color' => 'text-indigo-600', 'title' => 'Latihan Coding Interaktif', 'desc' => 'Tulis dan modifikasi kode langsung di web-editor dengan petunjuk soal terstruktur serta contoh implementasi real-time.'],
                    ['icon' => 'shield-check', 'bg' => 'bg-emerald-100', 'color' => 'text-emerald-600', 'title' => 'Penilaian Kode Otomatis', 'desc' => 'Sistem auto-grading instan mengevaluasi ketepatan logikamu dengan puluhan skenario uji tersembunyi secara adil dan objektif.'],
                    ['icon' => 'message-square', 'bg' => 'bg-orange-100', 'color' => 'text-orange-600', 'title' => 'Feedback Instan & Ramah', 'desc' => 'Pesan koreksi yang solutif menjelaskan kesalahan pengetikan dan sintaks secara bahasa manusia tanpa jargon rumit yang membuat pusing.'],
                    ['icon' => 'line-chart', 'bg' => 'bg-cyan-100', 'color' => 'text-cyan-600', 'title' => 'Pantau Progress Belajar', 'desc' => 'Dashboard personal untuk memantau modul yang telah kamu tuntaskan, streak harian, dan arsip riwayat pengerjaan tugas.'],
                ];
            @endphp

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($fitur as $i => $item)
                    <div class="reveal reveal-delay-{{ $i % 3 + 1 }} bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="w-11 h-11 rounded-xl {{ $item['bg'] }} flex items-center justify-center mb-5">
                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 {{ $item['color'] }}"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2">{{ $item['title'] }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $item['desc'] }}</p>
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
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">
                    3 Langkah Mudah Menguasai Python
                </h2>
                <p class="text-gray-600">
                    Alur ringkas yang memastikan kamu tidak hanya sekadar membaca teori, melainkan langsung membangun refleks coding praktis.
                </p>
            </div>

            @php
                $langkah = [
                    ['no' => 1, 'bg' => 'bg-blue-100', 'color' => 'text-blue-600', 'title' => 'Pelajari Materi', 'desc' => 'Baca penjelasan konsep dasar Python yang ringkas dan dilengkapi contoh kode nyata yang aplikatif untuk kebutuhan harian.'],
                    ['no' => 2, 'bg' => 'bg-yellow-100', 'color' => 'text-yellow-600', 'title' => 'Tulis & Jalankan Kode', 'desc' => 'Praktikkan langsung instruksi tugas di in-browser code editor. Ubah variabel dan lihat perubahannya seketika.'],
                    ['no' => 3, 'bg' => 'bg-emerald-100', 'color' => 'text-emerald-600', 'title' => 'Dapatkan Penilaian Otomatis', 'desc' => 'Kirim kode kamu untuk dinilai bot penguji otomatis. Dapatkan verifikasi test case lulus beserta skor instan dan tips optimalisasi.'],
                ];
            @endphp

            <div class="grid md:grid-cols-3 gap-6">
                @foreach ($langkah as $step)
                    <div class="reveal reveal-delay-{{ $loop->iteration }} bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                        <div class="w-9 h-9 rounded-lg {{ $step['bg'] }} {{ $step['color'] }} font-bold flex items-center justify-center mb-5">
                            {{ $step['no'] }}
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $step['desc'] }}</p>
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
                    <span class="font-bold text-gray-900">PyBeginner</span>
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

        <div class="max-w-7xl mx-auto px-6 lg:px-10 mt-10 pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-between gap-3 text-xs text-gray-500">
            <span>&copy; {{ date('Y') }} Tim PBL Kelompok 5 - SIB 3B</span>
            <span class="font-mono-code">print("Selamat belajar dan terus berkarya!")</span>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        // Scroll spy untuk navbar aktif secara dinamis
        const trackedSections = document.querySelectorAll('section[id]');
        const desktopNavLinks = document.querySelectorAll('.nav-link');

        function updateActiveNav() {
            let currentId = 'beranda';
            const scrollPos = window.scrollY + 140;

            trackedSections.forEach(section => {
                if (scrollPos >= section.offsetTop) {
                    currentId = section.getAttribute('id');
                }
            });

            desktopNavLinks.forEach(link => {
                const targetHash = link.getAttribute('href');
                if (targetHash === '#' + currentId) {
                    link.classList.remove('text-gray-600');
                    link.classList.add('text-gray-900', 'border-b-2', 'border-emerald-600', 'pb-1');
                } else {
                    link.classList.remove('text-gray-900', 'border-b-2', 'border-emerald-600', 'pb-1');
                    link.classList.add('text-gray-600');
                }
            });
        }

        window.addEventListener('scroll', updateActiveNav, { passive: true });
        updateActiveNav();
    </script>
</body>
</html>