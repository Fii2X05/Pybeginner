<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Submission - PyBeginner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    {{-- Fallback CDN Tailwind jika Vite belum di-build --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .font-mono-code { font-family: ui-monospace, 'Fira Code', 'Courier New', monospace; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#f0fdf4', 100: '#dcfce7', 300: '#86efac', 500: '#10b981', 600: '#059669', 700: '#047857' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white text-gray-900 antialiased min-h-screen flex flex-col">

    {{-- ===================== NAVBAR ===================== --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-gray-100">
        <nav class="max-w-7xl mx-auto px-6 lg:px-10 h-16 flex items-center justify-between">
            <div class="flex items-center gap-10">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-8 w-auto">
                </a>

                <ul class="hidden md:flex items-center gap-7 text-sm font-medium text-gray-600">
                    <li><a href="{{ route('beranda') }}" class="hover:text-emerald-600 transition-colors">Beranda</a></li>
                    <li><a href="{{ route('modul.index') }}" class="hover:text-emerald-600 transition-colors">Modul Belajar</a></li>
                    <li><a href="{{ route('latihan.index') }}" class="hover:text-emerald-600 transition-colors">Latihan Coding</a></li>
                    <li><a href="{{ route('submissions.history') }}" class="text-gray-900 border-b-2 border-emerald-600 pb-1">Riwayat Submission</a></li>
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

    {{-- ===================== HEADER SECTION ===================== --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-white via-emerald-50/70 to-emerald-50">
        <div class="hidden lg:block absolute top-0 right-0 w-96 h-96 bg-yellow-100/50 rounded-full blur-3xl z-0"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 py-10">
            <div class="flex items-center gap-3 mb-4 text-xs font-semibold">
                <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                    PYTHON 3.11 RUNTIME
                </span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/mascot-python.png') }}" alt="Mascot" class="w-16 h-16 object-contain object-top">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Riwayat Submission</h1>
                        <p class="text-gray-600 mt-2 max-w-2xl">Pantau progres pengiriman kode, hasil evaluasi test case, dan umpan balik langsung.</p>
                    </div>
                </div>
                <a href="{{ route('latihan.index') }}" class="inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white font-semibold px-5 py-2.5 rounded-xl transition-colors whitespace-nowrap">
                    <i data-lucide="square-code" class="w-4 h-4"></i>
                    Lanjutkan Latihan Terakhir
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== KONTEN UTAMA ===================== --}}
    <section class="bg-emerald-50 py-10 flex-grow">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            
            <!-- Search & Filter Bar -->
            <div class="bg-white p-2 rounded-xl border border-gray-200 shadow-sm flex flex-wrap justify-between items-center mb-6 gap-4">
                <div class="flex items-center px-3 w-full md:w-auto flex-grow max-w-md text-sm">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 mr-2"></i>
                    <input type="text" placeholder="Cari berdasarkan judul latihan atau nomor submission..." class="w-full focus:outline-none text-gray-600 placeholder-gray-400 bg-transparent">
                </div>
                <div class="flex items-center gap-4 text-xs font-medium overflow-x-auto whitespace-nowrap pb-1 md:pb-0">
                    <span class="text-brand-600 cursor-pointer font-bold">Semua ({{ $totalSubmissions }})</span>
                    <span class="text-gray-500 hover:text-gray-700 cursor-pointer">Berhasil ({{ $perfectScores }})</span>
                    <span class="text-gray-500 hover:text-gray-700 cursor-pointer">Perbaikan ({{ $needsImprovement }})</span>
                    <div class="h-4 w-px bg-gray-200 hidden md:block"></div>
                    <select class="bg-gray-50 border border-gray-200 text-gray-700 rounded-md px-3 py-1.5 outline-none cursor-pointer">
                        <option>Modul 03: Percabangan Python</option>
                    </select>
                </div>
            </div>

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <!-- Total Pengiriman -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold tracking-wide mb-1">TOTAL PENGIRIMAN</p>
                        <h2 class="text-3xl font-extrabold text-gray-900">{{ $totalSubmissions }} <span class="text-sm font-medium text-gray-400">Kali</span></h2>
                        <p class="text-[11px] font-medium text-brand-600 mt-2 flex items-center gap-1">
                            <i data-lucide="trending-up" class="w-3 h-3"></i> +8 minggu ini
                        </p>
                    </div>
                    <div class="bg-blue-100 p-2.5 rounded-xl text-blue-600">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </div>
                </div>

                <!-- Lolos Sempurna -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold tracking-wide mb-1">LOLOS SEMPURNA</p>
                        <h2 class="text-3xl font-extrabold text-gray-900">{{ $perfectScores }} <span class="text-sm font-medium text-gray-400">Soal</span></h2>
                        <p class="text-[11px] font-medium text-brand-600 mt-2 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span> Sukses {{ $totalSubmissions > 0 ? round(($perfectScores / $totalSubmissions) * 100, 1) : 0 }}%
                        </p>
                    </div>
                    <div class="bg-emerald-100 p-2.5 rounded-xl text-emerald-600">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                    </div>
                </div>

                <!-- Perlu Perbaikan -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold tracking-wide mb-1">PERLU PERBAIKAN</p>
                        <h2 class="text-3xl font-extrabold text-yellow-600">{{ $needsImprovement }} <span class="text-sm font-medium text-gray-400">Kali</span></h2>
                        <p class="text-[11px] font-medium text-gray-500 mt-2 flex items-center gap-1">
                            <i data-lucide="refresh-cw" class="w-3 h-3"></i> Dapat diperbaiki
                        </p>
                    </div>
                    <div class="bg-yellow-100 p-2.5 rounded-xl text-yellow-600">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                </div>

                <!-- Rata-rata Skor -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold tracking-wide mb-1">RATA-RATA SKOR</p>
                        <h2 class="text-3xl font-extrabold text-brand-700">{{ $averageScore }} <span class="text-sm font-medium text-gray-400">/ 100</span></h2>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mt-3">
                            <div class="h-full bg-brand-500 rounded-full" style="width: {{ min($averageScore, 100) }}%"></div>
                        </div>
                    </div>
                    <div class="bg-purple-100 p-2.5 rounded-xl text-purple-600">
                        <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>

            <!-- Main Layout Split -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- KIRI: Daftar Submission -->
                <div class="lg:col-span-1 flex flex-col h-full">
                    <div class="flex justify-between items-center mb-3 px-1">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">5 SUBMISSION TERKINI</p>
                        <p class="text-[10px] font-bold text-brand-600 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span> Realtime
                        </p>
                    </div>
                    
                    <div class="space-y-3 flex-grow">
                        @forelse($submissions as $sub)
                            @php $isActive = $selectedSubmission && $selectedSubmission->id === $sub->id; @endphp
                            <a href="{{ route('submissions.history', ['selected' => $sub->id]) }}" 
                               class="block bg-white rounded-2xl p-5 transition shadow-sm border {{ $isActive ? 'border-emerald-300 ring-1 ring-emerald-200' : 'border-gray-100 hover:border-emerald-200' }}">
                                
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-xs font-mono-code font-semibold text-brand-700">#SUB-{{ $sub->id }} <span class="text-gray-400 font-sans ml-1">• {{ $sub->submitted_at->diffForHumans() }}</span></span>
                                    @if($sub->score == 100)
                                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full flex items-center gap-1 border border-emerald-100">
                                            <i data-lucide="check" class="w-3 h-3"></i> 100/100
                                        </span>
                                    @else
                                        <span class="text-xs font-bold text-yellow-700 bg-yellow-50 px-2 py-0.5 rounded-full border border-yellow-200">
                                            {{ round($sub->score) }}/100 ({{ $sub->passed_tests }}/{{ $sub->total_tests }})
                                        </span>
                                    @endif
                                </div>
                                
                                <h3 class="font-bold text-gray-900 text-sm mb-1">Latihan {{ sprintf('%02d', $sub->exercise_id) }} — Cek Bilangan Genap atau Ganjil</h3>
                                <p class="text-xs text-gray-500 mb-4">Modul 03: Percabangan Python</p>
                                
                                <div class="flex justify-between items-center text-xs pt-3 border-t border-gray-50">
                                    <span class="flex items-center gap-1.5 text-gray-500 font-medium">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i> 
                                        Waktu: {{ $sub->execution_time_ms ? ($sub->execution_time_ms / 1000) . 's' : '0.05s' }}
                                    </span>
                                    <span class="{{ $isActive ? 'text-brand-700 font-bold flex items-center gap-1' : 'text-gray-500 font-semibold hover:text-brand-600' }}">
                                        {{ $isActive ? 'Sedang Dilihat' : 'Lihat Detail' }} <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center text-gray-400 text-sm">
                                Belum ada riwayat submission.
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-between items-center mt-6 px-2 text-xs font-medium text-gray-500">
                        <span class="cursor-pointer hover:text-gray-800">Sebelumnya</span>
                        <div class="flex space-x-2">
                            <span class="w-7 h-7 flex items-center justify-center bg-brand-600 text-white rounded-lg cursor-pointer">1</span>
                            <span class="w-7 h-7 flex items-center justify-center hover:bg-white border border-transparent hover:border-gray-200 rounded-lg cursor-pointer">2</span>
                            <span class="w-7 h-7 flex items-center justify-center hover:bg-white border border-transparent hover:border-gray-200 rounded-lg cursor-pointer">3</span>
                        </div>
                        <span class="cursor-pointer hover:text-brand-700 text-brand-600 font-semibold">Berikutnya</span>
                    </div>
                </div>

                <!-- KANAN: Detail Submission Terpilih -->
                <div class="lg:col-span-2">
                    @if($selectedSubmission)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:p-8">
                            
                            <!-- Header Detail -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-xs font-mono-code font-semibold text-gray-500">#SUB-{{ $selectedSubmission->id }}</span>
                                        @if($selectedSubmission->score == 100)
                                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full flex items-center gap-1 border border-emerald-100">
                                                <i data-lucide="check" class="w-3 h-3"></i> Lolos Semua Uji (100/100)
                                            </span>
                                        @else
                                            <span class="text-xs font-bold text-yellow-700 bg-yellow-50 px-2.5 py-0.5 rounded-full border border-yellow-200">
                                                Sebagian Lolos ({{ $selectedSubmission->passed_tests }}/{{ $selectedSubmission->total_tests }})
                                            </span>
                                        @endif
                                    </div>
                                    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900">Cek Bilangan Genap atau Ganjil</h2>
                                </div>
                                <a href="/latihan" class="flex items-center gap-2 text-sm font-semibold text-gray-700 bg-gray-50 border border-gray-200 px-4 py-2 rounded-xl hover:bg-gray-100 transition whitespace-nowrap">
                                    <i data-lucide="external-link" class="w-4 h-4"></i> Buka Editor
                                </a>
                            </div>

                            <!-- Dark Code Editor -->
                            <div class="bg-[#0b1120] rounded-xl overflow-hidden mb-8 shadow-inner border border-gray-800">
                                <div class="flex items-center justify-between px-4 py-3 bg-[#111827] border-b border-gray-800">
                                    <div class="flex gap-2">
                                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                    </div>
                                    <span class="text-xs font-mono-code text-gray-400">solution.py</span>
                                    <span class="text-xs font-mono-code text-gray-500 flex items-center gap-2">
                                        Python 3.11 <i data-lucide="copy" class="w-3.5 h-3.5 cursor-pointer hover:text-white"></i>
                                    </span>
                                </div>
                                <div class="p-5 text-sm font-mono-code overflow-x-auto leading-relaxed">
                                    <pre><code class="text-gray-300"><span class="text-gray-500"># Solusi Latihan dikirim oleh {{ auth()->user()->name ?? 'Sukma Ananda' }}</span>
<span class="text-blue-400">angka</span> = <span class="text-emerald-400">int</span>(<span class="text-emerald-400">input</span>())

<span class="text-purple-400">if</span> <span class="text-blue-400">angka</span> % <span class="text-orange-300">2</span> == <span class="text-orange-300">0</span>:
    <span class="text-emerald-400">print</span>(<span class="text-yellow-300">"Genap"</span>)
<span class="text-purple-400">else</span>:
    <span class="text-emerald-400">print</span>(<span class="text-yellow-300">"Ganjil"</span>)</code></pre>
                                </div>
                            </div>

                            <!-- Test Case Header -->
                            <div class="flex justify-between items-center mb-4 pb-2">
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">HASIL EVALUASI TEST CASE</h3>
                                <span class="text-xs font-bold text-gray-700 bg-gray-100 px-3 py-1 rounded-full">
                                    {{ $selectedSubmission->passed_tests }} / {{ $selectedSubmission->total_tests }} Uji Berhasil
                                </span>
                            </div>

                            <!-- Test Case List -->
                            <div class="space-y-4 mb-8">
                                @forelse($selectedSubmission->results as $index => $result)
                                    @php $isPassed = strtolower($result->status) === 'accepted'; @endphp
                                    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm {{ $isPassed ? 'border-l-4 border-l-emerald-400' : 'border-l-4 border-l-red-400' }}">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-sm font-bold flex items-center gap-2 text-gray-900">
                                                @if($isPassed)
                                                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                                                @else
                                                    <i data-lucide="x-circle" class="w-5 h-5 text-red-500"></i>
                                                @endif
                                                Test Case {{ $index + 1 }}: {{ $isPassed ? 'Input Sesuai Ekspektasi' : 'Output Tidak Cocok' }}
                                            </span>
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-md {{ $isPassed ? 'text-emerald-700 bg-emerald-50' : 'text-red-700 bg-red-50' }}">
                                                {{ $isPassed ? 'Lolos' : 'Gagal' }} • {{ $result->execution_time_ms ?? '12' }}ms
                                            </span>
                                        </div>

                                        <div class="flex flex-col gap-3 text-xs font-mono-code mt-2">
                                            <div class="flex flex-col sm:flex-row sm:gap-4 border-b border-gray-100 pb-3">
                                                <span class="w-20 text-gray-400 mb-1 sm:mb-0">Input:</span>
                                                <span class="text-gray-800 bg-gray-50 px-2 py-1 rounded">{{ $result->testCase->stdin ?? '4' }}</span>
                                            </div>
                                            <div class="flex flex-col sm:flex-row sm:gap-4 pt-1">
                                                <span class="w-20 text-gray-400 mb-1 sm:mb-0">Output:</span>
                                                <span class="text-gray-800 bg-gray-50 px-2 py-1 rounded">"{{ trim($result->actual_output ?? 'Genap') }}"</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-xs text-gray-400 text-center py-4">Detail test case tidak ditemukan.</div>
                                @endforelse
                            </div>

                            <!-- Feedback Box -->
                            <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-5 flex gap-4 mb-8">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="lightbulb" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 mb-1.5">Umpan Balik Cepat</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">Penggunaan operator modulus <span class="bg-white border border-gray-200 px-1.5 py-0.5 rounded text-gray-800 font-mono-code text-xs">% 2</span> tepat dan efisien dalam membagi ruang bilangan genap/ganjil.</p>
                                </div>
                            </div>

                            <!-- Action Footer -->
                            <div class="flex flex-col sm:flex-row justify-between items-center border-t border-gray-100 pt-6 gap-4">
                                <span class="text-sm text-gray-500">Poin XP Modul: <span class="font-extrabold text-brand-600">+25 XP</span></span>
                                <button class="w-full sm:w-auto bg-yellow-400 text-gray-900 px-6 py-3 rounded-xl text-sm font-bold hover:bg-yellow-500 transition flex items-center justify-center gap-2">
                                    Lanjut ke Latihan Berikutnya <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 flex flex-col items-center justify-center text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                <i data-lucide="mouse-pointer-click" class="w-8 h-8"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada yang Dipilih</h3>
                            <p class="text-sm text-gray-500 max-w-sm">Pilih salah satu riwayat submission di panel sebelah kiri untuk melihat detail eksekusi kode dan hasil test case.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="border-t border-gray-100 py-14 bg-white mt-auto">
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
                    <li><a href="#" class="hover:text-emerald-600 transition-colors">Python Dasar</a></li>
                    <li><a href="#" class="hover:text-emerald-600 transition-colors">Struktur Data</a></li>
                    <li><a href="#" class="hover:text-emerald-600 transition-colors">Algoritma & Logika</a></li>
                    <li><a href="#" class="hover:text-emerald-600 transition-colors">OOP Python</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-gray-900 mb-4">Fitur Belajar</h4>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-emerald-600 transition-colors">Pembelajaran Interaktif</a></li>
                    <li><a href="#" class="hover:text-emerald-600 transition-colors">Kuis & Tantangan</a></li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-10 mt-10 pt-6 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500">
            <div>&copy; {{ date('Y') }} Tim PBL Kelompok 5 - SIB 3B</div>
            <div class="font-mono-code text-gray-400">print("Selamat belajar dan terus berkarya!")</div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>