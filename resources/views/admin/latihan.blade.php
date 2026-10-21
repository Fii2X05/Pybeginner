<x-layouts.admin title="Latihan & Soal - Admin" active="latihan">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
                <span>BANK SOAL</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Latihan &amp; Soal
            </h1>
            <p class="text-sm text-slate-500 mt-1">Daftar soal coding interaktif yang dikerjakan oleh siswa pada platform.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3.5 py-1.5 rounded-xl bg-emerald-100/70 text-emerald-800 text-xs font-bold">
                Total {{ $exercises->count() }} Latihan
            </span>
        </div>
    </div>

    <!-- Table of Exercises -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Urutan</th>
                        <th class="py-3.5 px-6">Judul Latihan</th>
                        <th class="py-3.5 px-6">Modul &amp; Pelajaran</th>
                        <th class="py-3.5 px-6 text-center">Tingkat Kesulitan</th>
                        <th class="py-3.5 px-6 text-center">Limit Eksekusi</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($exercises as $exercise)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-bold text-slate-400">
                                #{{ sprintf('%02d', $exercise->sort_order) }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $exercise->title }}</div>
                                <div class="text-xs font-mono text-slate-400 mt-0.5">{{ $exercise->slug }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-600 text-xs">
                                <div class="font-semibold text-slate-800">{{ $exercise->lesson->title ?? 'Umum' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $exercise->lesson->module->title ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if (strtolower($exercise->difficulty) === 'easy' || strtolower($exercise->difficulty) === 'mudah')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Mudah
                                    </span>
                                @elseif (strtolower($exercise->difficulty) === 'medium' || strtolower($exercise->difficulty) === 'sedang')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                        Sedang
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                        Sulit
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center text-xs text-slate-500 font-mono">
                                {{ $exercise->time_limit_ms ?? 2000 }}ms / {{ $exercise->memory_limit_mb ?? 128 }}MB
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('latihan.show', $exercise->slug) }}" target="_blank" 
                                   class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 hover:text-emerald-700 px-3 py-1.5 rounded-lg hover:bg-emerald-50 transition">
                                    <span>Tinjau Soal</span>
                                    <i data-lucide="play" class="w-3.5 h-3.5"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 px-6 text-center text-slate-400 text-sm">
                                Belum ada latihan coding. Jalankan seeder latihan terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
