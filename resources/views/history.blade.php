<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Submission - PyBeginner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 
                            50: '#f0fdf4', 
                            100: '#dcfce7', 
                            500: '#10b981', 
                            600: '#059669', 
                            700: '#047857' // Warna hijau gelap khas desainmu
                        },
                        accent: {
                            blue: '#1d4ed8',
                            yellow: '#fbbf24'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#f8fafc] text-gray-800 font-sans min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-100 px-8 py-3 flex justify-between items-center">
        <div class="flex items-center">
            <!-- Logo PyBeginner -->
            <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner Logo" class="h-8">
        </div>
        <div class="hidden md:flex space-x-8 text-sm font-medium text-gray-500">
            <a href="#" class="hover:text-gray-900">Beranda</a>
            <a href="/modul" class="hover:text-gray-900">Modul Belajar</a>
            <a href="/latihan" class="hover:text-gray-900">Latihan Coding</a>
            <!-- Garis bawah biru seperti pada desain -->
            <a href="{{ route('submissions.history') }}" class="text-blue-700 border-b-2 border-blue-700 pb-4 -mb-4">Riwayat Submission</a>
        </div>
        <div class="flex items-center space-x-5">
            <button class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </button>
            <div class="flex items-center space-x-2 cursor-pointer">
                <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Sukma Ananda' }}</span>
                <div class="w-8 h-8 bg-brand-700 rounded-full flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-6 py-8 max-w-7xl">
        
        <!-- Header Section -->
        <div class="mb-6">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-700 bg-brand-50 px-2.5 py-1 rounded-md mb-3 border border-brand-100">
                <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span> Python 3.11 Runtime
            </span>
            <div class="flex justify-between items-end">
                <div class="flex items-center gap-4">
                    <!-- Maskot Ular -->
                    <img src="{{ asset('images/submission-python.png') }}" alt="Mascot" class="w-14 h-14 rounded-full object-cover shadow-sm">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Riwayat Submission</h1>
                        <p class="text-sm text-gray-500 mt-1">Pantau progres pengiriman kode, hasil evaluasi test case, dan umpan balik langsung.</p>
                    </div>
                </div>
                <a href="/latihan" class="bg-brand-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-brand-800 transition flex items-center gap-2">
                    Lanjutkan Latihan Terakhir &rarr;
                </a>
            </div>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white p-2 rounded-xl border border-gray-200 shadow-sm flex flex-wrap justify-between items-center mb-6 gap-4">
            <div class="flex items-center px-3 w-full md:w-auto flex-grow max-w-md text-sm">
                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Cari berdasarkan judul latihan atau nomor submission..." class="w-full focus:outline-none text-gray-600 placeholder-gray-400 bg-transparent">
            </div>
            <div class="flex items-center gap-4 text-xs font-medium">
                <span class="text-brand-600 cursor-pointer">Semua ({{ $totalSubmissions }})</span>
                <span class="text-gray-500 hover:text-gray-700 cursor-pointer">Berhasil ({{ $perfectScores }})</span>
                <span class="text-gray-500 hover:text-gray-700 cursor-pointer">Perbaikan ({{ $needsImprovement }})</span>
                <div class="h-4 w-px bg-gray-200"></div>
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
                    <p class="text-xs text-gray-500 font-medium mb-1">Total Pengiriman</p>
                    <h2 class="text-3xl font-bold text-gray-900">{{ $totalSubmissions }} <span class="text-sm font-normal text-gray-500">Kali</span></h2>
                    <p class="text-[11px] font-medium text-brand-600 mt-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        +8 pengiriman minggu ini
                    </p>
                </div>
                <div class="bg-blue-50 p-2.5 rounded-full text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Lolos Sempurna -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex justify-between items-start">
                <div>
                    <p class="text-xs text-gray-500 font-medium mb-1">Lolos Sempurna (100%)</p>
                    <h2 class="text-3xl font-bold text-gray-900">{{ $perfectScores }} <span class="text-sm font-normal text-gray-500">Soal</span></h2>
                    <p class="text-[11px] font-medium text-brand-600 mt-2 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span> Tingkat sukses {{ $totalSubmissions > 0 ? round(($perfectScores / $totalSubmissions) * 100, 1) : 0 }}%
                    </p>
                </div>
                <div class="bg-brand-50 p-2.5 rounded-full text-brand-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Perlu Perbaikan -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex justify-between items-start">
                <div>
                    <p class="text-xs text-gray-500 font-medium mb-1">Perlu Perbaikan</p>
                    <h2 class="text-3xl font-bold text-yellow-600">{{ $needsImprovement }} <span class="text-sm font-normal text-gray-500">Kali</span></h2>
                    <p class="text-[11px] font-medium text-gray-500 mt-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Dapat diperbaiki kapan saja
                    </p>
                </div>
                <div class="bg-yellow-50 p-2.5 rounded-full text-yellow-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>
            </div>

            <!-- Rata-rata Skor -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex justify-between items-start">
                <div>
                    <p class="text-xs text-gray-500 font-medium mb-1">Rata-rata Skor</p>
                    <h2 class="text-3xl font-bold text-brand-700 border-b-2 border-brand-500 inline-block pb-0.5">{{ $averageScore }} <span class="text-sm font-normal text-gray-500">/ 100</span></h2>
                </div>
                <div class="bg-blue-50 p-2.5 rounded-full text-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Main Layout Split -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- KIRI: Daftar Submission -->
            <div class="lg:col-span-1 flex flex-col h-full">
                <div class="flex justify-between items-center mb-3 px-1">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">MENAMPILKAN 5 SUBMISSION TERKINI</p>
                    <p class="text-[10px] font-bold text-brand-600 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span> Diperbarui Realtime
                    </p>
                </div>
                
                <div class="space-y-3 flex-grow">
                    @forelse($submissions as $sub)
                        @php $isActive = $selectedSubmission && $selectedSubmission->id === $sub->id; @endphp
                        <a href="{{ route('submissions.history', ['selected' => $sub->id]) }}" 
                           class="block bg-white rounded-xl p-4 transition shadow-sm border {{ $isActive ? 'border-brand-500 ring-1 ring-brand-500 shadow-md' : 'border-gray-200 hover:border-brand-300' }}">
                            
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-mono font-medium text-brand-600">#SUB-{{ $sub->id }} <span class="text-gray-400 font-sans ml-1">• {{ $sub->submitted_at->diffForHumans() }}</span></span>
                                @if($sub->score == 100)
                                    <span class="text-xs font-bold text-brand-600 bg-white px-2 py-0.5 rounded-full border border-brand-500 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        100/100
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-yellow-600 bg-white px-2 py-0.5 rounded-full border border-yellow-400">
                                        {{ round($sub->score) }}/100 ({{ $sub->passed_tests }}/{{ $sub->total_tests }})
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="font-bold text-gray-900 text-sm mb-1">Latihan {{ sprintf('%02d', $sub->exercise_id) }} — Cek Bilangan Genap atau Ganjil</h3>
                            <p class="text-xs text-gray-400 mb-4">Modul 03: Percabangan Python</p>
                            
                            <div class="flex justify-between items-center text-xs pt-3 border-t border-gray-50">
                                <span class="flex items-center gap-1 text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                    Waktu: {{ $sub->execution_time_ms ? ($sub->execution_time_ms / 1000) . 's' : '0.05s' }}
                                </span>
                                <span class="{{ $isActive ? 'text-brand-700 font-semibold' : 'text-gray-500 font-medium' }}">
                                    {{ $isActive ? 'Sedang Dilihat >' : 'Lihat Detail' }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="bg-white p-6 rounded-xl border border-gray-200 text-center text-gray-400 text-sm">
                            Belum ada riwayat submission.
                        </div>
                    @endforelse
                </div>

                <!-- Pagination Sesuai Desain -->
                <div class="flex justify-between items-center mt-6 px-2 text-xs font-medium text-gray-500">
                    <span class="cursor-pointer hover:text-gray-800">Sebelumnya</span>
                    <div class="flex space-x-2">
                        <span class="w-6 h-6 flex items-center justify-center bg-brand-700 text-white rounded-md cursor-pointer">1</span>
                        <span class="w-6 h-6 flex items-center justify-center hover:bg-gray-100 rounded-md cursor-pointer">2</span>
                        <span class="w-6 h-6 flex items-center justify-center hover:bg-gray-100 rounded-md cursor-pointer">3</span>
                    </div>
                    <span class="cursor-pointer hover:text-gray-800 text-brand-700">Berikutnya</span>
                </div>
            </div>

            <!-- KANAN: Detail Submission Terpilih -->
            <div class="lg:col-span-2">
                @if($selectedSubmission)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 lg:p-8">
                        
                        <!-- Header Detail -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-mono font-medium text-gray-500">#SUB-{{ $selectedSubmission->id }}</span>
                                    @if($selectedSubmission->score == 100)
                                        <span class="text-xs font-bold text-brand-600 bg-white px-2 py-0.5 rounded-full border border-brand-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Lolos Semua Uji (100/100)
                                        </span>
                                    @else
                                        <span class="text-xs font-bold text-yellow-600 bg-white px-2 py-0.5 rounded-full border border-yellow-400">
                                            Sebagian Lolos ({{ $selectedSubmission->passed_tests }}/{{ $selectedSubmission->total_tests }})
                                        </span>
                                    @endif
                                </div>
                                <h2 class="text-xl font-bold text-gray-900">Cek Bilangan Genap atau Ganjil</h2>
                            </div>
                            <a href="/latihan" class="flex items-center gap-2 text-xs font-semibold text-brand-700 bg-white border border-brand-500 px-3 py-2 rounded-md hover:bg-brand-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                Buka di Code Editor
                            </a>
                        </div>

                        <!-- Dark Code Editor (Sesuai Desain Hitam/Navy Dark) -->
                        <div class="bg-[#0b1120] rounded-lg overflow-hidden mb-8 shadow-inner border border-gray-800">
                            <!-- Mac Titlebar -->
                            <div class="flex items-center justify-between px-4 py-2.5 bg-[#111827] border-b border-gray-800">
                                <div class="flex gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-yellow-500"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                                </div>
                                <span class="text-xs font-mono text-gray-400">solution.py</span>
                                <span class="text-xs font-mono text-gray-400 flex items-center gap-2">
                                    Python 3.11
                                    <svg class="w-3.5 h-3.5 cursor-pointer hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </span>
                            </div>
                            <div class="p-4 text-sm font-mono overflow-x-auto leading-relaxed">
                                <!-- Pewarnaan Sintaks Sederhana Manual -->
                                <pre><code class="text-gray-300"><span class="text-gray-500"># Solusi Latihan dikirim oleh {{ auth()->user()->name ?? 'Sukma Ananda' }}</span>
<span class="text-blue-400">angka</span> = <span class="text-brand-300">int</span>(<span class="text-brand-300">input</span>())

<span class="text-purple-400">if</span> <span class="text-blue-400">angka</span> % <span class="text-orange-300">2</span> == <span class="text-orange-300">0</span>:
    <span class="text-brand-300">print</span>(<span class="text-yellow-300">"Genap"</span>)
<span class="text-purple-400">else</span>:
    <span class="text-brand-300">print</span>(<span class="text-yellow-300">"Ganjil"</span>)</code></pre>
                            </div>
                        </div>

                        <!-- Test Case Header -->
                        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">HASIL EVALUASI TEST CASE</h3>
                            <span class="text-xs font-bold text-brand-600 bg-white px-2 py-0.5 rounded-full border border-brand-400">
                                {{ $selectedSubmission->passed_tests }} dari {{ $selectedSubmission->total_tests }} Lolos ({{ round($selectedSubmission->score) }}%)
                            </span>
                        </div>

                        <!-- Test Case List -->
                        <div class="space-y-3 mb-6">
                            @forelse($selectedSubmission->results as $index => $result)
                                @php $isPassed = strtolower($result->status) === 'accepted'; @endphp
                                <div class="bg-white border rounded-lg p-4 {{ $isPassed ? 'border-gray-200 border-l-2 border-l-brand-400' : 'border-gray-200 border-l-2 border-l-red-400' }}">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-sm font-semibold flex items-center gap-2 text-gray-800">
                                            @if($isPassed)
                                                <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @else
                                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            @endif
                                            Test Case {{ $index + 1 }}: {{ $isPassed ? 'Input Genap Positif' : 'Gagal' }}
                                        </span>
                                        <span class="text-xs font-bold px-2 py-1 rounded-md {{ $isPassed ? 'text-brand-700 bg-brand-50' : 'text-red-700 bg-red-50' }}">
                                            {{ $isPassed ? 'Lolos' : 'Gagal' }} • {{ $result->execution_time_ms ?? '12' }}ms
                                        </span>
                                    </div>

                                    <div class="flex flex-col gap-2 text-xs font-mono text-gray-500 mt-2">
                                        <div class="flex gap-4 border-b border-dotted border-gray-200 pb-2">
                                            <span class="w-16 text-gray-400">Input:</span>
                                            <span class="text-gray-800">{{ $result->testCase->stdin ?? '4' }}</span>
                                        </div>
                                        <div class="flex gap-4 pt-1">
                                            <span class="w-16 text-gray-400">Output:</span>
                                            <span class="text-gray-800">"{{ trim($result->actual_output ?? 'Genap') }}"</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-xs text-gray-400 text-center py-4">Detail test case tidak ditemukan.</div>
                            @endforelse
                        </div>

                        <!-- Feedback Box (Umpan Balik) -->
                        <div class="bg-brand-50 border border-brand-200 rounded-lg p-4 flex gap-3 mb-8">
                            <div class="w-6 h-6 rounded-md bg-brand-700 text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 mb-1">Umpan Balik Cepat:</h4>
                                <p class="text-xs text-gray-600 leading-relaxed">Penggunaan operator modulus <span class="bg-white border border-gray-200 px-1 py-0.5 rounded text-gray-800 font-mono">% 2</span> tepat dan efisien dalam membagi ruang bilangan genap/ganjil.</p>
                            </div>
                        </div>

                        <!-- Action Footer -->
                        <div class="flex justify-between items-center border-t border-gray-100 pt-6">
                            <span class="text-sm text-gray-500">Poin diperoleh: <span class="font-bold text-gray-900">+25 XP</span></span>
                            <button class="bg-[#facc15] text-gray-900 px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-yellow-500 transition flex items-center gap-2 shadow-sm">
                                Lanjut ke Latihan Berikutnya &rarr;
                            </button>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center text-gray-400">
                        Pilih salah satu submission di sebelah kiri untuk melihat detail.
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#f1f5fb] py-12 mt-auto">
        <div class="container mx-auto px-8 max-w-7xl grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-1">
                <span class="text-lg font-bold text-blue-800 block mb-3">Py<span class="text-brand-600">Beginner</span></span>
                <p class="text-xs text-gray-500 leading-relaxed">Platform interaktif modern untuk menguasai pemrograman Python mulai dari nol secara terstruktur dan ramah pemula.</p>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 text-sm mb-3">Kurikulum</h4>
                <ul class="text-xs text-gray-500 space-y-2">
                    <li><a href="#" class="hover:text-gray-900">Python Dasar</a></li>
                    <li><a href="#" class="hover:text-gray-900">Struktur Data</a></li>
                    <li><a href="#" class="hover:text-gray-900">Algoritma & Logika</a></li>
                    <li><a href="#" class="hover:text-gray-900">OOP Python</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 text-sm mb-3">Fitur Belajar</h4>
                <ul class="text-xs text-gray-500 space-y-2">
                    <li><a href="#" class="hover:text-gray-900">Interactive REPL</a></li>
                    <li><a href="#" class="hover:text-gray-900">Kuis & Tantangan</a></li>
                    <li><a href="#" class="hover:text-gray-900">Sertifikasi</a></li>
                    <li><a href="#" class="hover:text-gray-900">Forum Tanya Jawab</a></li>
                </ul>
            </div>
        </div>
        <div class="container mx-auto px-8 max-w-7xl flex flex-col md:flex-row justify-between items-center mt-12 pt-6 border-t border-gray-200">
            <p class="text-xs text-gray-400">&copy; 2026 Tim PBL Kelompok 5 - SIB 3B</p>
            <div class="text-[10px] font-mono text-gray-400 mt-4 md:mt-0">
                print("Selamat belajar dan terus berkarya!")
            </div>
        </div>
    </footer>
</body>
</html>