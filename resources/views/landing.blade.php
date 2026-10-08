<x-layouts.app title="Belajar Python Interaktif & Gratis untuk Pemula" active="beranda" navbar="landing" :flush="true">

    {{-- ===================== HERO ===================== --}}
    <section id="beranda" class="relative scroll-mt-24 overflow-hidden bg-emerald-50">
        <img src="{{ asset('images/hero-illustration.png') }}" alt=""
             class="absolute inset-0 z-0 h-full w-full object-cover object-left">

        <div class="relative z-10 mx-auto grid max-w-7xl items-center gap-12 px-6 py-16 lg:grid-cols-5 lg:px-10 lg:py-24">

            {{-- Teks --}}
            <div class="lg:col-span-3">
                <span class="mb-6 inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold text-emerald-800">
                    <i data-lucide="zap" class="h-3.5 w-3.5"></i>
                    Belajar Python Interaktif &amp; Gratis untuk Pemula
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
    <section id="fitur" class="scroll-mt-24 bg-emerald-50 py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="mx-auto mb-14 max-w-2xl text-center">
                <span class="mb-4 inline-block rounded-full bg-emerald-100 px-4 py-1.5 text-xs font-semibold text-emerald-800">
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
    <section id="metode" class="scroll-mt-24 bg-white py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="mx-auto mb-14 max-w-2xl text-center">
                <span class="mb-4 inline-block rounded-full bg-yellow-100 px-4 py-1.5 text-xs font-semibold text-yellow-700">
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

    {{-- ===================== CTA ===================== --}}
    <section id="mulai-coding" class="scroll-mt-24 bg-white pb-20">
        <div class="mx-auto max-w-6xl px-6 lg:px-10">
            <div class="reveal relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-700 to-emerald-500 px-8 py-16 text-center sm:px-16">
                <div class="pointer-events-none absolute -right-10 -top-10 h-44 w-44 rounded-full bg-white/10"></div>
                <div class="pointer-events-none absolute -bottom-12 left-16 h-32 w-32 rounded-full bg-yellow-300/20"></div>

                <div class="relative">
                    <span class="mb-6 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold text-white">
                        <i data-lucide="rocket" class="h-3.5 w-3.5"></i>
                        Akses 100% Gratis
                    </span>

                    <h2 class="mb-4 text-3xl font-extrabold text-white sm:text-4xl">
                        Siap Menulis Kode Python Pertamamu Hari Ini?
                    </h2>
                    <p class="mx-auto mb-8 max-w-xl text-emerald-50">
                        Buka soal pertamamu sekarang dan buktikan bahwa coding itu mudah dan menyenangkan.
                    </p>

                    <div class="flex flex-col justify-center gap-4 sm:flex-row">
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-yellow-400 px-6 py-3.5 font-semibold text-slate-900 shadow-sm transition hover:bg-yellow-300">
                            Mulai Belajar Gratis <i data-lucide="rocket" class="h-4 w-4"></i>
                        </a>
                        <a href="{{ route('latihan.index') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-6 py-3.5 font-semibold text-slate-900 transition hover:bg-emerald-50">
                            Coba Latihan Coding
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>