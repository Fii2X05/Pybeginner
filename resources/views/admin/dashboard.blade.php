<x-layouts.admin title="Dashboard Admin" active="dashboard">
    <!-- Header Title & Tag -->
    <div class="mb-8">
        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
            <span>KURIKULUM PYTHON</span>
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
        </div>
        <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
            DASHBOARD ADMIN
        </h1>
    </div>

    <!-- Stat Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Modul -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400">Total Modul</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalModul }} Modul</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i data-lucide="layers" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Total Latihan -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400">Total Latihan</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalLatihan }} Soal</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i data-lucide="terminal" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Total Siswa (Bonus stat) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-xs flex items-center justify-between sm:col-span-2 lg:col-span-1">
            <div>
                <p class="text-xs font-semibold text-slate-400">Total Siswa Terdaftar</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalSiswa }} Siswa</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Content Columns (Aktivitas Terbaru & Info Modul) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Aktivitas Terbaru Card (Sesuai Mockup) -->
        <div class="lg:col-span-1 bg-white rounded-2xl p-6 border border-slate-100 shadow-xs">
            <h2 class="text-base font-bold text-slate-900 mb-4">Aktivitas Terbaru</h2>

            <div class="space-y-4">
                @forelse ($recentActivities as $index => $activity)
                    @if ($index > 0)
                        <div class="border-t border-slate-100 pt-4">
                    @else
                        <div>
                    @endif
                        <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $activity['name'] }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $activity['action'] }}</p>
                        <span class="inline-block text-[11px] text-slate-400 mt-1">{{ $activity['time'] }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic">Belum ada aktivitas terbaru.</p>
                @endforelse
            </div>
        </div>

        <!-- Ringkasan Modul & Pintasan -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-900">Akses Cepat Modul Pembelajaran</h2>
                    <a href="{{ route('admin.materi') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                        Lihat Semua <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a href="{{ route('admin.materi') }}" class="p-3.5 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/40 transition group">
                        <div class="text-xs font-semibold text-emerald-600">Modul 01 - 03</div>
                        <div class="text-sm font-bold text-slate-800 group-hover:text-emerald-700 mt-0.5">Dasar &amp; Logika Percabangan</div>
                        <div class="text-[11px] text-slate-400 mt-1">Variabel, Tipe Data, Sintaksis Dasar</div>
                    </a>

                    <a href="{{ route('admin.materi') }}" class="p-3.5 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/40 transition group">
                        <div class="text-xs font-semibold text-emerald-600">Modul 04 - 05</div>
                        <div class="text-sm font-bold text-slate-800 group-hover:text-emerald-700 mt-0.5">Perulangan &amp; Fungsi</div>
                        <div class="text-[11px] text-slate-400 mt-1">While Loop, For Loop, Modularitas</div>
                    </a>

                    <a href="{{ route('admin.materi') }}" class="p-3.5 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/40 transition group">
                        <div class="text-xs font-semibold text-emerald-600">Modul 06</div>
                        <div class="text-sm font-bold text-slate-800 group-hover:text-emerald-700 mt-0.5">Struktur Data Python</div>
                        <div class="text-[11px] text-slate-400 mt-1">List, Tuple, Dictionary, Set</div>
                    </a>

                    <a href="{{ route('admin.materi') }}" class="p-3.5 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/40 transition group">
                        <div class="text-xs font-semibold text-emerald-600">Modul 07</div>
                        <div class="text-sm font-bold text-slate-800 group-hover:text-emerald-700 mt-0.5">Pengantar Pandas &amp; NumPy</div>
                        <div class="text-[11px] text-slate-400 mt-1">Manipulasi Data &amp; Komputasi Dasar</div>
                    </a>
                </div>
            </div>

            <!-- Quick Action Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('admin.latihan') }}" class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-5 text-white shadow-sm flex items-center justify-between hover:opacity-95 transition">
                    <div>
                        <div class="text-xs font-medium text-emerald-100 uppercase tracking-wide">Bank Soal</div>
                        <div class="text-base font-bold mt-1">Kelola Latihan &amp; Soal</div>
                        <div class="text-xs text-emerald-100/80 mt-1">Lihat dan tinjau 15 soal coding aktif</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                        <i data-lucide="code" class="w-5 h-5 text-white"></i>
                    </div>
                </a>

                <a href="{{ route('admin.penilaian') }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs flex items-center justify-between hover:border-emerald-200 transition">
                    <div>
                        <div class="text-xs font-medium text-slate-400 uppercase tracking-wide">Hasil Pembelajaran</div>
                        <div class="text-base font-bold text-slate-900 mt-1">Penilaian Siswa</div>
                        <div class="text-xs text-slate-400 mt-1">Tinjau submission dan nilai submission siswa</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <i data-lucide="check-square" class="w-5 h-5"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-layouts.admin>
