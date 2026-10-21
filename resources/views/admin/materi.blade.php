<x-layouts.admin title="Materi Belajar - Admin" active="materi">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
                <span>MANAJEMEN KONTEN</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Materi Belajar
            </h1>
            <p class="text-sm text-slate-500 mt-1">Daftar seluruh modul dan silabus kurikulum pembelajaran Python dasar.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3.5 py-1.5 rounded-xl bg-emerald-100/70 text-emerald-800 text-xs font-bold">
                Total {{ $modules->count() }} Modul
            </span>
        </div>
    </div>

    <!-- Table of Modules -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Urutan</th>
                        <th class="py-3.5 px-6">Judul Modul</th>
                        <th class="py-3.5 px-6">Slug</th>
                        <th class="py-3.5 px-6 text-center">Jumlah Lesson</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($modules as $module)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-bold text-slate-400">
                                #{{ sprintf('%02d', $module->sort_order) }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $module->title }}</div>
                                <div class="text-xs text-slate-400 line-clamp-1 mt-0.5">{{ $module->description }}</div>
                            </td>
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">
                                {{ $module->slug }}
                            </td>
                            <td class="py-4 px-6 text-center font-semibold text-slate-700">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    {{ $module->lessons_count ?? 0 }} Pelajaran
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if ($module->is_published)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('modul.index') }}" target="_blank" 
                                   class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 hover:text-emerald-700 px-3 py-1.5 rounded-lg hover:bg-emerald-50 transition">
                                    <span>Buka Modul</span>
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 px-6 text-center text-slate-400 text-sm">
                                Tidak ada modul ditemukan. Jalankan seeder modul terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
