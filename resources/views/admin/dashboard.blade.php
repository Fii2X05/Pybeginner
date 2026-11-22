<x-layouts.admin title="Dashboard Monitoring - Admin" active="dashboard">
    <!-- Header Title & Description -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
                <span>PUSAT PEMANTAUAN PEMBELAJARAN</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Dashboard Monitoring &amp; Penilaian
            </h1>
            <p class="text-sm text-slate-500 mt-1">Pantau progres learner, evaluasi autograder per latihan, dan statistik nilai platform secara real-time.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.latihan') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:border-emerald-300 hover:text-emerald-700 text-xs font-bold transition shadow-2xs">
                <i data-lucide="code" class="w-4 h-4 text-emerald-600"></i>
                <span>Bank Soal</span>
            </a>
            <a href="{{ route('admin.penilaian') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 text-xs font-bold transition shadow-2xs shadow-emerald-600/20">
                <i data-lucide="check-square" class="w-4 h-4"></i>
                <span>Semua Penilaian</span>
            </a>
        </div>
    </div>

    <!-- Stat Cards Row (4 KPI Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Total Siswa -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400">Total Siswa Terdaftar</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalSiswa }}</h3>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 mt-1">
                    <i data-lucide="activity" class="w-3 h-3"></i>
                    <span>{{ $activeLearners }} aktif mengerjakan</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Total Latihan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400">Total Soal Latihan</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalLatihan }} Soal</h3>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400 mt-1">
                    <i data-lucide="layers" class="w-3 h-3"></i>
                    <span>{{ $totalModul }} modul kurikulum</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i data-lucide="terminal" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Total Submissions & Pass Rate -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400">Total Evaluasi Masuk</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalSubmissions }}</h3>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 mt-1">
                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                    <span>Tingkat Lolos {{ $passRate }}%</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <i data-lucide="send" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Rata-rata Skor Platform -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400">Rata-rata Skor Platform</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $avgScore }} <span class="text-sm font-normal text-slate-400">/ 100</span></h3>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold {{ $avgScore >= 75 ? 'text-emerald-600' : 'text-amber-600' }} mt-1">
                    <i data-lucide="award" class="w-3 h-3"></i>
                    <span>{{ $avgScore >= 75 ? 'Performa Sangat Baik' : 'Perlu Evaluasi Soal' }}</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <i data-lucide="trending-up" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Section 1: PENILAIAN PERSOAL (Analisis Kinerja Autograder Tiap Latihan) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 tracking-wider uppercase">
                    <i data-lucide="bar-chart-2" class="w-3.5 h-3.5"></i>
                    <span>MONITORING EVALUASI PERSOAL</span>
                </div>
                <h2 class="text-lg font-black text-slate-900 mt-0.5">Penilaian &amp; Performa per Latihan Coding</h2>
                <p class="text-xs text-slate-500 mt-0.5">Analisis tingkat keberhasilan siswa pada masing-masing soal untuk mendeteksi soal yang sulit atau membutuhkan bimbingan tambahan.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.latihan') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                    <span>Atur Bank Soal</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Urutan</th>
                        <th class="py-3.5 px-6">Judul Latihan</th>
                        <th class="py-3.5 px-6 text-center">Tingkat</th>
                        <th class="py-3.5 px-6 text-center">Submission</th>
                        <th class="py-3.5 px-6">Tingkat Kelulusan (Pass Rate)</th>
                        <th class="py-3.5 px-6 text-center">Rata-rata Skor</th>
                        <th class="py-3.5 px-6 text-center">Diagnostik</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($exercisesStats as $ex)
                        @php
                            $subCount = $ex->submissions_count ?? 0;
                            $passCount = $ex->passed_count ?? 0;
                            $exPassRate = $subCount > 0 ? round(($passCount / $subCount) * 100) : 0;
                            $avgExScore = round((float) ($ex->submissions_avg_score ?? 0), 1);
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-bold text-slate-400 font-mono text-xs">
                                #{{ sprintf('%02d', $ex->sort_order) }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $ex->title }}</div>
                                <div class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $ex->slug }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if (strtolower($ex->difficulty) === 'easy')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Pemula
                                    </span>
                                @elseif (strtolower($ex->difficulty) === 'medium')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                        Menengah
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                        Sulit
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-slate-800">
                                {{ $subCount }} kali
                            </td>
                            <td class="py-4 px-6 min-w-[180px]">
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="font-bold {{ $exPassRate >= 70 ? 'text-emerald-700' : ($exPassRate >= 40 ? 'text-amber-700' : 'text-rose-700') }}">
                                        {{ $exPassRate }}% Lolos
                                    </span>
                                    <span class="text-slate-400 text-[11px]">{{ $passCount }}/{{ $subCount }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full {{ $exPassRate >= 70 ? 'bg-emerald-500' : ($exPassRate >= 40 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                         style="width: {{ $exPassRate }}%"></div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center font-black text-slate-900">
                                {{ $avgExScore }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if ($subCount === 0)
                                    <span class="text-xs text-slate-400 italic">Belum dikerjakan</span>
                                @elseif ($exPassRate >= 80)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg">
                                        <i data-lucide="check-check" class="w-3 h-3"></i>
                                        <span>Tuntas Dipahami</span>
                                    </span>
                                @elseif ($exPassRate >= 50)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg">
                                        <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                        <span>Cukup Menantang</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-lg">
                                        <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                                        <span>Banyak Gagal</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('latihan.show', $ex->slug) }}" target="_blank"
                                   class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 px-2.5 py-1 rounded-lg hover:bg-emerald-50 transition">
                                    <span>Tinjau</span>
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 px-6 text-center text-slate-400 text-sm">
                                Belum ada latihan coding tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 2: PENILAIAN PER LEARNER & LIVE SUBMISSIONS ROW -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Penilaian per Learner / Siswa Monitoring (2 Kolom di Desktop) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <div class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 tracking-wider uppercase">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                        <span>MONITORING KINERJA SISWA</span>
                    </div>
                    <h2 class="text-lg font-black text-slate-900 mt-0.5">Penilaian per Siswa Teraktif</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar siswa yang aktif mengirimkan kode beserta tingkat keberhasilan dan rata-rata nilainya.</p>
                </div>
                <a href="{{ route('admin.siswa') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                    <span>Semua Siswa</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-6">Siswa</th>
                            <th class="py-3 px-6 text-center">Submission</th>
                            <th class="py-3 px-6 text-center">Soal Lolos</th>
                            <th class="py-3 px-6 text-center">Rata-rata Nilai</th>
                            <th class="py-3 px-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($learnerStats as $student)
                            @php
                                $sScore = round((float) ($student->submissions_avg_score ?? 0), 1);
                                $sPassed = $student->passed_count ?? 0;
                                $sTotal = $student->submissions_count ?? 0;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6">
                                    <div class="font-bold text-slate-900">{{ $student->name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $student->email }}</div>
                                </td>
                                <td class="py-3.5 px-6 text-center font-bold text-slate-700">
                                    {{ $sTotal }} kali
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700">
                                        {{ $sPassed }} Lolos
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-center font-black text-slate-900">
                                    <span class="{{ $sScore >= 75 ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $sScore }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    @if ($sScore >= 85)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                            Sangat Baik
                                        </span>
                                    @elseif ($sScore >= 65)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700">
                                            Berkembang
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700">
                                            Perlu Bimbingan
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-6 text-center text-slate-400 text-sm">
                                    Belum ada aktivitas submission dari siswa.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Kolom Kanan: Akses Cepat & Status Modul Kurikulum -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-900">Kurikulum &amp; Silabus</h2>
                    <a href="{{ route('admin.materi') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                        Lihat Semua <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <div class="space-y-2.5">
                    <a href="{{ route('admin.materi') }}" class="p-3 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/40 transition flex items-center justify-between group">
                        <div>
                            <div class="text-[11px] font-bold text-emerald-600">Modul 01 - 03</div>
                            <div class="text-xs font-bold text-slate-800 group-hover:text-emerald-700">Dasar Sintaks &amp; Percabangan</div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-emerald-600"></i>
                    </a>

                    <a href="{{ route('admin.materi') }}" class="p-3 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/40 transition flex items-center justify-between group">
                        <div>
                            <div class="text-[11px] font-bold text-emerald-600">Modul 04 - 05</div>
                            <div class="text-xs font-bold text-slate-800 group-hover:text-emerald-700">Perulangan &amp; Fungsi Modular</div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-emerald-600"></i>
                    </a>

                    <a href="{{ route('admin.materi') }}" class="p-3 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/40 transition flex items-center justify-between group">
                        <div>
                            <div class="text-[11px] font-bold text-emerald-600">Modul 06 - 07</div>
                            <div class="text-xs font-bold text-slate-800 group-hover:text-emerald-700">Struktur Data, NumPy &amp; Pandas</div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-emerald-600"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Action Cards -->
            <a href="{{ route('admin.latihan') }}" class="block bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-5 text-white shadow-sm hover:opacity-95 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium text-emerald-100 uppercase tracking-wide">Kelola Bank Soal</div>
                        <div class="text-base font-bold mt-1">Pengaturan Soal &amp; Batas Memori</div>
                        <div class="text-xs text-emerald-100/80 mt-1">Cek keunikan slug, urutan, &amp; limit Judge0</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                        <i data-lucide="code" class="w-5 h-5 text-white"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Section 3: LIVE FEED RIWAYAT EVALUASI TERBARU -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <div class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 tracking-wider uppercase">
                    <i data-lucide="radio" class="w-3.5 h-3.5 text-emerald-600"></i>
                    <span>LIVE EVALUATION FEED</span>
                </div>
                <h2 class="text-lg font-black text-slate-900 mt-0.5">Riwayat Evaluasi Kode Submission Terbaru</h2>
                <p class="text-xs text-slate-500 mt-0.5">Hasil eksekusi autograder dan pengujian test case solusi kode dari siswa.</p>
            </div>
            <a href="{{ route('admin.penilaian') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                <span>Lihat Seluruh Riwayat</span>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-6">Waktu</th>
                        <th class="py-3 px-6">Siswa</th>
                        <th class="py-3 px-6">Latihan / Soal</th>
                        <th class="py-3 px-6 text-center">Test Passed</th>
                        <th class="py-3 px-6 text-center">Skor</th>
                        <th class="py-3 px-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentSubmissions as $sub)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-6 text-xs text-slate-500">
                                {{ $sub->submitted_at ? $sub->submitted_at->diffForHumans() : ($sub->created_at ? $sub->created_at->diffForHumans() : 'Baru saja') }}
                            </td>
                            <td class="py-3.5 px-6">
                                <div class="font-bold text-slate-900">{{ $sub->user->name ?? 'Siswa' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $sub->user->email ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-6">
                                <div class="font-semibold text-slate-800">{{ $sub->exercise->title ?? 'Latihan Coding' }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $sub->exercise->slug ?? '' }}</div>
                            </td>
                            <td class="py-3.5 px-6 text-center font-mono text-xs text-slate-600">
                                {{ $sub->passed_tests ?? 0 }} / {{ $sub->total_tests ?? 0 }}
                            </td>
                            <td class="py-3.5 px-6 text-center font-black text-base">
                                <span class="{{ ($sub->score ?? 0) >= 70 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $sub->score ?? 0 }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                @if (strtolower($sub->status) === 'accepted' || strtolower($sub->status) === 'passed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Lolos
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                        {{ ucfirst($sub->status ?? 'Gagal') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 px-6 text-center text-slate-400 text-sm">
                                Belum ada submission terbaru yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
