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
            <a href="{{ route('admin.latihan.create') }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-4 py-2 rounded-xl transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Latihan
            </a>
        </div>
    </div>

    <!-- Table of Exercises -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[960px] text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 pl-6 pr-3 w-16 whitespace-nowrap">#</th>
                        <th class="py-3.5 px-4">Judul Latihan</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap">Tingkat</th>
                        <th class="py-3.5 px-4 text-center whitespace-nowrap">Status</th>
                        <th class="py-3.5 pl-4 pr-6 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($exercises as $exercise)
                        @php
                            $difficulty = strtolower($exercise->difficulty);
                            $testCaseTotal = $testCaseCounts[$exercise->id] ?? 0;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition align-middle">
                            <td class="py-4 pl-6 pr-3 font-bold text-slate-400 whitespace-nowrap">
                                {{ sprintf('%02d', $exercise->sort_order) }}
                            </td>

                            <td class="py-4 px-4 min-w-[280px]">
                                <div class="font-bold text-slate-900 leading-snug">{{ $exercise->title }}</div>
                                <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-slate-500">
                                    <span>{{ $exercise->lesson->module->title ?? 'Umum' }}</span>
                                    <span class="text-slate-300">&bull;</span>
                                    <span>{{ $exercise->lesson->title ?? '-' }}</span>
                                </div>
                                <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-0.5 text-[11px] text-slate-400">
                                    <span class="font-mono">{{ $exercise->slug }}</span>
                                    <span class="text-slate-300">&bull;</span>
                                    <span>{{ $exercise->time_limit_ms ?? 2000 }} ms / {{ $exercise->memory_limit_mb ?? 128 }} MB</span>
                                </div>
                            </td>

                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                @if ($difficulty === 'easy' || $difficulty === 'mudah')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Mudah</span>
                                @elseif ($difficulty === 'medium' || $difficulty === 'sedang')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">Sedang</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">Sulit</span>
                                @endif
                            </td>

                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.latihan.status.update', $exercise) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="is_published" aria-label="Status {{ $exercise->title }}" onchange="this.form.submit()"
                                            class="rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs font-semibold {{ $exercise->is_published ? 'text-emerald-700' : 'text-slate-500' }} focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="1" @selected($exercise->is_published)>Terbit</option>
                                        <option value="0" @selected(! $exercise->is_published)>Draft</option>
                                    </select>
                                </form>
                            </td>

                            <td class="py-4 pl-4 pr-6">
                                <div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                                    <a href="{{ route('admin.latihan.test-cases', $exercise) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 border rounded-lg transition {{ $testCaseTotal > 0 ? 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200' : 'bg-amber-50 hover:bg-amber-100 text-amber-800 border-amber-200' }}">
                                        <i data-lucide="list-checks" class="w-3.5 h-3.5"></i>
                                        Test Case ({{ $testCaseTotal }})
                                    </a>
                                    <a href="{{ route('admin.latihan.edit', $exercise) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg transition">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        Edit
                                    </a>
                                    @if ($exercise->is_published)
                                        <a href="{{ route('latihan.show', $exercise->slug) }}" target="_blank"
                                           class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Tinjau soal di halaman siswa" aria-label="Tinjau {{ $exercise->title }}">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.latihan.destroy', $exercise) }}"
                                          onsubmit="return confirm('Hapus latihan ini? Test case dan riwayat submission siswa untuk soal ini ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus latihan" aria-label="Hapus {{ $exercise->title }}">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 px-6 text-center text-slate-400 text-sm">
                                Belum ada latihan coding. Klik "Tambah Latihan" atau jalankan seeder latihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>