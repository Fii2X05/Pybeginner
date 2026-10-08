<x-layouts.app title="Riwayat Submission" active="history">

    {{-- ===================== HEADER SECTION ===================== --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-white via-emerald-50/70 to-emerald-50">
        <div class="hidden lg:block absolute top-0 right-0 w-96 h-96 bg-yellow-100/50 rounded-full blur-3xl z-0"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 py-10">
            <div class="flex items-center gap-3 mb-4 text-xs font-semibold">
                <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                    PYTHON 3.7.7 RUNTIME
                </span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/mascot-python.png') }}" alt="Mascot" class="w-16 h-16 object-contain object-top drop-shadow-sm transition duration-300 hover:-rotate-6 hover:scale-105">
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
    <section class="bg-emerald-50 py-10 flex-grow pb-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            
            <!-- Search & Filter Bar -->
            <div class="bg-white p-2 rounded-2xl border border-gray-200 shadow-sm flex flex-wrap justify-between items-center mb-6 gap-4">
                <div class="flex items-center px-4 w-full md:w-auto flex-grow max-w-md text-sm">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 mr-2"></i>
                    <input type="text" placeholder="Cari berdasarkan judul latihan atau nomor submission..." class="w-full focus:outline-none text-gray-600 placeholder-gray-400 bg-transparent py-2">
                </div>
                <div class="flex items-center gap-4 text-xs font-medium overflow-x-auto whitespace-nowrap pb-1 md:pb-0 px-2">
                    <span class="text-brand-600 cursor-pointer font-bold">Semua ({{ $totalSubmissions }})</span>
                    <span class="text-gray-500 hover:text-gray-700 cursor-pointer">Berhasil ({{ $perfectScores }})</span>
                    <span class="text-gray-500 hover:text-gray-700 cursor-pointer">Perbaikan ({{ $needsImprovement }})</span>
                    <div class="h-4 w-px bg-gray-200 hidden md:block"></div>
                    <select class="bg-gray-50 border border-gray-200 text-gray-700 rounded-lg px-3 py-2 outline-none cursor-pointer focus:ring-2 focus:ring-emerald-500">
                        <option>Modul 03: Percabangan Python</option>
                    </select>
                </div>
            </div>

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <!-- Total Pengiriman -->
                <div class="group relative overflow-hidden bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:ring-1 hover:ring-emerald-200 flex justify-between items-start z-10">
                    <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-blue-50 transition duration-300 group-hover:scale-150 -z-10"></div>
                    <div class="relative">
                        <p class="text-xs text-gray-500 font-semibold tracking-wide mb-1">TOTAL PENGIRIMAN</p>
                        <h2 class="text-3xl font-extrabold text-gray-900">{{ $totalSubmissions }} <span class="text-sm font-medium text-gray-400">Kali</span></h2>
                        <p class="text-[11px] font-medium text-blue-600 mt-2 flex items-center gap-1">
                            <i data-lucide="trending-up" class="w-3 h-3"></i> +8 minggu ini
                        </p>
                    </div>
                    <div class="relative bg-blue-100 p-3 rounded-2xl text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </div>
                </div>

                <!-- Lolos Sempurna -->
                <div class="group relative overflow-hidden bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:ring-1 hover:ring-emerald-200 flex justify-between items-start z-10">
                    <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-emerald-50 transition duration-300 group-hover:scale-150 -z-10"></div>
                    <div class="relative">
                        <p class="text-xs text-gray-500 font-semibold tracking-wide mb-1">LOLOS SEMPURNA</p>
                        <h2 class="text-3xl font-extrabold text-gray-900">{{ $perfectScores }} <span class="text-sm font-medium text-gray-400">Soal</span></h2>
                        <p class="text-[11px] font-medium text-emerald-600 mt-2 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sukses {{ $totalSubmissions > 0 ? round(($perfectScores / $totalSubmissions) * 100, 1) : 0 }}%
                        </p>
                    </div>
                    <div class="relative bg-emerald-100 p-3 rounded-2xl text-emerald-600 transition group-hover:bg-emerald-600 group-hover:text-white">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                    </div>
                </div>

                <!-- Perlu Perbaikan -->
                <div class="group relative overflow-hidden bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:ring-1 hover:ring-emerald-200 flex justify-between items-start z-10">
                    <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-yellow-50 transition duration-300 group-hover:scale-150 -z-10"></div>
                    <div class="relative">
                        <p class="text-xs text-gray-500 font-semibold tracking-wide mb-1">PERLU PERBAIKAN</p>
                        <h2 class="text-3xl font-extrabold text-yellow-600">{{ $needsImprovement }} <span class="text-sm font-medium text-gray-400">Kali</span></h2>
                        <p class="text-[11px] font-medium text-gray-500 mt-2 flex items-center gap-1">
                            <i data-lucide="refresh-cw" class="w-3 h-3"></i> Dapat diperbaiki
                        </p>
                    </div>
                    <div class="relative bg-yellow-100 p-3 rounded-2xl text-yellow-600 transition group-hover:bg-yellow-500 group-hover:text-white">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                </div>

                <!-- Rata-rata Skor -->
                <div class="group relative overflow-hidden bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:ring-1 hover:ring-emerald-200 flex justify-between items-start z-10">
                    <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-purple-50 transition duration-300 group-hover:scale-150 -z-10"></div>
                    <div class="relative w-full pr-4">
                        <p class="text-xs text-gray-500 font-semibold tracking-wide mb-1">RATA-RATA SKOR</p>
                        <h2 class="text-3xl font-extrabold text-brand-700">{{ $averageScore }} <span class="text-sm font-medium text-gray-400">/ 100</span></h2>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mt-3">
                            <div class="h-full bg-brand-500 rounded-full" style="width: {{ min($averageScore, 100) }}%"></div>
                        </div>
                    </div>
                    <div class="relative bg-purple-100 p-3 rounded-2xl text-purple-600 transition group-hover:bg-purple-600 group-hover:text-white">
                        <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>

            <!-- Main Layout Split -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- KIRI: Daftar Submission -->
                <div class="lg:col-span-1 flex flex-col h-full">
                    <div class="flex justify-between items-center mb-4 px-1">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">5 SUBMISSION TERKINI</p>
                        <p class="text-[10px] font-bold text-brand-600 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span> Realtime
                        </p>
                    </div>
                    
                    <div class="space-y-4 flex-grow">
                        @forelse($submissions as $sub)
                            @php $isActive = $selectedSubmission && $selectedSubmission->id === $sub->id; @endphp
                            <a href="{{ route('submissions.history', ['selected' => $sub->id]) }}" 
                               class="block group relative overflow-hidden bg-white rounded-3xl p-6 shadow-sm ring-1 transition duration-200 hover:-translate-y-1 hover:shadow-md hover:ring-emerald-300 z-10 {{ $isActive ? 'ring-emerald-400 shadow-md bg-emerald-50/20' : 'ring-emerald-100' }}">
                                
                                <!-- Hover Blob untuk item list -->
                                <div class="absolute -right-6 -top-6 h-20 w-20 rounded-full bg-emerald-50 transition duration-300 group-hover:scale-150 -z-10"></div>
                                
                                <div class="relative">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="text-xs font-mono-code font-semibold text-brand-700">#SUB-{{ $sub->id }} <span class="text-gray-400 font-sans ml-1 font-normal">• {{ $sub->submitted_at->diffForHumans() }}</span></span>
                                        @if($sub->score == 100)
                                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg flex items-center gap-1 border border-emerald-100">
                                                <i data-lucide="check" class="w-3 h-3"></i> 100/100
                                            </span>
                                        @else
                                            <span class="text-xs font-bold text-yellow-700 bg-yellow-50 px-2.5 py-1 rounded-lg border border-yellow-200">
                                                {{ round($sub->score) }}/100 <span class="font-normal">({{ $sub->passed_tests }}/{{ $sub->total_tests }})</span>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <h3 class="font-bold text-gray-900 text-sm mb-1">Latihan {{ sprintf('%02d', $sub->exercise_id) }} — Cek Bilangan Genap atau Ganjil</h3>
                                    <p class="text-xs text-gray-500 mb-5">Modul 03: Percabangan Python</p>
                                    
                                    <div class="flex justify-between items-center text-xs pt-4 border-t border-gray-100">
                                        <span class="flex items-center gap-1.5 text-gray-500 font-medium">
                                            <i data-lucide="clock" class="w-3.5 h-3.5"></i> 
                                            {{ $sub->execution_time_ms ? ($sub->execution_time_ms / 1000) . 's' : '0.05s' }}
                                        </span>
                                        <span class="{{ $isActive ? 'text-brand-700 font-bold flex items-center gap-1' : 'text-gray-500 font-semibold group-hover:text-brand-600 transition flex items-center gap-1' }}">
                                            {{ $isActive ? 'Sedang Dilihat' : 'Lihat Detail' }} 
                                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 transition group-hover:translate-x-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="bg-white p-8 rounded-3xl border border-gray-200 text-center text-gray-400 text-sm">
                                Belum ada riwayat submission.
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-between items-center mt-6 px-2 text-xs font-medium text-gray-500">
                        <span class="cursor-pointer hover:text-gray-800 transition">Sebelumnya</span>
                        <div class="flex space-x-2">
                            <span class="w-8 h-8 flex items-center justify-center bg-brand-600 text-white rounded-xl shadow-sm cursor-pointer">1</span>
                            <span class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 hover:border-emerald-300 hover:text-emerald-700 rounded-xl transition cursor-pointer">2</span>
                            <span class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 hover:border-emerald-300 hover:text-emerald-700 rounded-xl transition cursor-pointer">3</span>
                        </div>
                        <span class="cursor-pointer hover:text-brand-800 text-brand-600 font-semibold transition">Berikutnya</span>
                    </div>
                </div>

                <!-- KANAN: Detail Submission Terpilih -->
                <div class="lg:col-span-2">
                    @if($selectedSubmission)
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 lg:p-8">
                            
                            <!-- Header Detail -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="text-xs font-mono-code font-semibold text-gray-500">#SUB-{{ $selectedSubmission->id }}</span>
                                        @if($selectedSubmission->score == 100)
                                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg flex items-center gap-1.5 border border-emerald-100">
                                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Lolos Semua Uji (100/100)
                                            </span>
                                        @else
                                            <span class="text-xs font-bold text-yellow-700 bg-yellow-50 px-2.5 py-1 rounded-lg border border-yellow-200">
                                                Sebagian Lolos ({{ $selectedSubmission->passed_tests }}/{{ $selectedSubmission->total_tests }})
                                            </span>
                                        @endif
                                    </div>
                                    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900">Cek Bilangan Genap atau Ganjil</h2>
                                </div>
                                <a href="/latihan" class="flex items-center gap-2 text-sm font-semibold text-gray-700 bg-gray-50 border border-gray-200 px-5 py-2.5 rounded-xl hover:bg-gray-100 hover:shadow-sm transition whitespace-nowrap">
                                    <i data-lucide="external-link" class="w-4 h-4 text-gray-500"></i> Buka Editor
                                </a>
                            </div>

                            <!-- Dark Code Editor -->
                            <div class="bg-[#0b1120] rounded-2xl overflow-hidden mb-8 shadow-inner border border-gray-800">
                                <div class="flex items-center justify-between px-5 py-3.5 bg-[#111827] border-b border-gray-800">
                                    <div class="flex gap-2">
                                        <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                                        <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                                        <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                                    </div>
                                    <span class="text-xs font-mono-code text-gray-400">solution.py</span>
                                    <button class="text-xs font-mono-code text-gray-500 flex items-center gap-2 hover:text-white transition">
                                        Python 3.11 <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                                <div class="p-6 text-sm font-mono-code overflow-x-auto leading-relaxed">
                                    <pre><code class="text-gray-300"><span class="text-gray-500"># Solusi Latihan dikirim oleh {{ auth()->user()->name ?? 'Sukma Ananda' }}</span>
<span class="text-blue-400">angka</span> = <span class="text-emerald-400">int</span>(<span class="text-emerald-400">input</span>())

<span class="text-purple-400">if</span> <span class="text-blue-400">angka</span> % <span class="text-orange-300">2</span> == <span class="text-orange-300">0</span>:
    <span class="text-emerald-400">print</span>(<span class="text-yellow-300">"Genap"</span>)
<span class="text-purple-400">else</span>:
    <span class="text-emerald-400">print</span>(<span class="text-yellow-300">"Ganjil"</span>)</code></pre>
                                </div>
                            </div>

                            <!-- Test Case Header -->
                            <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">HASIL EVALUASI TEST CASE</h3>
                                <span class="text-xs font-bold text-gray-700 bg-gray-100 px-3 py-1.5 rounded-lg">
                                    {{ $selectedSubmission->passed_tests }} / {{ $selectedSubmission->total_tests }} Uji Berhasil
                                </span>
                            </div>

                            <!-- Test Case List -->
                            <div class="space-y-4 mb-8">
                                @forelse($selectedSubmission->results as $index => $result)
                                    @php $isPassed = strtolower($result->status) === 'accepted'; @endphp
                                    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm {{ $isPassed ? 'border-l-4 border-l-emerald-400' : 'border-l-4 border-l-red-400' }}">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-sm font-bold flex items-center gap-2.5 text-gray-900">
                                                @if($isPassed)
                                                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                                                @else
                                                    <i data-lucide="x-circle" class="w-5 h-5 text-red-500"></i>
                                                @endif
                                                Test Case {{ $index + 1 }}: {{ $isPassed ? 'Input Sesuai Ekspektasi' : 'Output Tidak Cocok' }}
                                            </span>
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ $isPassed ? 'text-emerald-700 bg-emerald-50' : 'text-red-700 bg-red-50' }}">
                                                {{ $isPassed ? 'Lolos' : 'Gagal' }} • {{ $result->execution_time_ms ?? '12' }}ms
                                            </span>
                                        </div>

                                        <div class="flex flex-col gap-3 text-xs font-mono-code mt-2">
                                            <div class="flex flex-col sm:flex-row sm:gap-4 border-b border-gray-50 pb-3">
                                                <span class="w-20 text-gray-400 mb-1 sm:mb-0">Input:</span>
                                                <span class="text-gray-800 bg-gray-50 px-2 py-1 rounded-md border border-gray-100">{{ $result->testCase->stdin ?? '4' }}</span>
                                            </div>
                                            <div class="flex flex-col sm:flex-row sm:gap-4 pt-1">
                                                <span class="w-20 text-gray-400 mb-1 sm:mb-0">Output:</span>
                                                <span class="text-gray-800 bg-gray-50 px-2 py-1 rounded-md border border-gray-100">"{{ trim($result->actual_output ?? 'Genap') }}"</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-xs text-gray-400 text-center py-4">Detail test case tidak ditemukan.</div>
                                @endforelse
                            </div>

                            <!-- Feedback Box -->
                            <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-6 flex gap-4 mb-8">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="lightbulb" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 mb-2">Umpan Balik Cepat</h4>
                                    <p class="text-sm text-gray-600 leading-relaxed">Penggunaan operator modulus <span class="bg-white border border-gray-200 px-1.5 py-0.5 rounded text-gray-800 font-mono-code text-xs shadow-sm">% 2</span> tepat dan efisien dalam membagi ruang bilangan genap/ganjil.</p>
                                </div>
                            </div>

                            <!-- Action Footer -->
                            <div class="flex flex-col sm:flex-row justify-between items-center border-t border-gray-100 pt-8 gap-4">
                                <span class="text-sm text-gray-500">Poin XP Modul: <span class="font-extrabold text-brand-600 bg-emerald-50 px-2.5 py-1 rounded-lg ml-1">+25 XP</span></span>
                                <button class="group w-full sm:w-auto bg-yellow-400 text-gray-900 px-6 py-3 rounded-xl text-sm font-bold hover:bg-yellow-300 hover:shadow-md transition flex items-center justify-center gap-2">
                                    Lanjut ke Latihan Berikutnya <i data-lucide="arrow-right" class="w-4 h-4 transition group-hover:translate-x-1"></i>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-16 flex flex-col items-center justify-center text-center h-full min-h-[400px]">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-5 text-gray-300">
                                <i data-lucide="mouse-pointer-click" class="w-10 h-10"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada yang Dipilih</h3>
                            <p class="text-sm text-gray-500 max-w-sm leading-relaxed">Pilih salah satu riwayat submission di panel sebelah kiri untuk melihat detail eksekusi kode dan hasil test case.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>