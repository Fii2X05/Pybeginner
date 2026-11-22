<x-layouts.admin title="Latihan & Soal - Admin" active="latihan">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
                <span>MANAJEMEN BANK SOAL</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Latihan &amp; Bank Soal Coding
            </h1>
            <p class="text-sm text-slate-500 mt-1">Kelola latihan interaktif, slug navigasi, limit memori runtime Judge0, dan urutan soal.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3.5 py-1.5 rounded-xl bg-emerald-100/70 text-emerald-800 text-xs font-bold">
                Total {{ $exercises->count() }} Latihan Aktif
            </span>
            <a href="{{ route('latihan.index') }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition shadow-2xs">
                <span>Buka Katalog Latihan Siswa</span>
                <i data-lucide="external-link" class="w-3.5 h-3.5 text-slate-400"></i>
            </a>
        </div>
    </div>

    <!-- Banner Edukasi: Slug, Navigasi, & Memori Tujuan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- Info Slug & Navigasi -->
        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                <i data-lucide="link" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-blue-900">Pengaruh Slug pada Navigasi</h4>
                <p class="text-xs text-blue-800/80 mt-0.5 leading-relaxed">
                    Siswa mengakses soal melalui rute <code class="bg-blue-100/80 px-1 py-0.5 rounded font-mono text-[11px]">/latihan/{slug}</code>. Jika terdapat dua soal dengan <strong>slug yang sama</strong>, sistem hanya akan membuka soal yang pertama ditemukan, sehingga soal kedua <strong>tidak akan bisa diakses</strong>. Pastikan setiap slug unik!
                </p>
            </div>
        </div>

        <!-- Info Memori Tujuan Judge0 -->
        <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                <i data-lucide="cpu" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-emerald-900">Memori Tujuan &amp; Batas Waktu</h4>
                <p class="text-xs text-emerald-800/80 mt-0.5 leading-relaxed">
                    <strong>Memori Tujuan</strong> adalah batas alokasi RAM sandbox compiler Judge0 (standar 128 MB). Jika kode siswa melampaui memori ini, autograder akan mengembalikan status <em>Memory Limit Exceeded (MLE)</em>. Batas waktu standar adalah 2000 ms - 10000 ms.
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Peringatan Jika Ada Slug Duplikat -->
    @if (!empty($duplicateSlugs))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 mt-0.5">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-rose-900">Peringatan: Ditemukan Slug Duplikat!</h4>
                <p class="text-xs text-rose-800/90 mt-0.5 leading-relaxed">
                    Slug berikut digunakan lebih dari satu latihan: <code class="bg-rose-200/60 px-1 py-0.5 rounded font-mono font-bold">{{ implode(', ', $duplicateSlugs) }}</code>. Harap ubah salah satu slug melalui tombol <strong>Edit</strong> agar kedua soal dapat dibuka secara normal oleh siswa.
                </p>
            </div>
        </div>
    @endif

    <!-- Table of Exercises -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Urutan</th>
                        <th class="py-3.5 px-6">Judul Latihan &amp; Slug</th>
                        <th class="py-3.5 px-6">Modul / Pelajaran</th>
                        <th class="py-3.5 px-6 text-center">Tingkat</th>
                        <th class="py-3.5 px-6 text-center">Total Soal Uji</th>
                        <th class="py-3.5 px-6 text-center">Batas Memori &amp; Waktu</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($exercises as $exercise)
                        @php
                            $isDuplicateSlug = in_array($exercise->slug, $duplicateSlugs ?? []);
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-mono font-bold text-slate-600">
                                <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-700 font-bold text-xs">
                                    #{{ sprintf('%02d', $exercise->sort_order) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <span>{{ $exercise->title }}</span>
                                    @if ($isDuplicateSlug)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200" title="Slug ini bentrok dengan soal lain!">
                                            <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                            <span>Duplikat</span>
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs font-mono {{ $isDuplicateSlug ? 'text-rose-600 font-bold' : 'text-slate-400' }} mt-0.5">
                                    /latihan/{{ $exercise->slug }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600 text-xs">
                                <div class="font-semibold text-slate-800">{{ $exercise->lesson->title ?? 'Umum' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $exercise->lesson->module->title ?? 'Python Dasar' }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if (strtolower($exercise->difficulty) === 'easy')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Pemula
                                    </span>
                                @elseif (strtolower($exercise->difficulty) === 'medium')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                        Menengah
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                        Sulit
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold">
                                    <i data-lucide="check-square" class="w-3 h-3"></i>
                                    <span>{{ $exercise->test_cases_count ?? 0 }} Test Case</span>
                                </span>
                                <div class="text-[10px] text-slate-400 mt-1">{{ $exercise->submissions_count ?? 0 }} submission</div>
                            </td>
                            <td class="py-4 px-6 text-center text-xs text-slate-600">
                                <div class="font-bold text-slate-800 font-mono">
                                    {{ $exercise->memory_limit_mb ?? 128 }} MB
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono">
                                    {{ ($exercise->time_limit_ms ?? 2000) / 1000 }} detik ({{ $exercise->time_limit_ms ?? 2000 }}ms)
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <!-- Tombol Trigger Edit Modal -->
                                    <button type="button" 
                                            onclick="document.getElementById('modal-edit-{{ $exercise->id }}').classList.remove('hidden')"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700 px-2.5 py-1.5 rounded-lg hover:bg-blue-50 transition border border-blue-200">
                                        <i data-lucide="edit-3" class="w-3 h-3"></i>
                                        <span>Edit</span>
                                    </button>

                                    <!-- Tombol Tinjau Soal -->
                                    <a href="{{ route('latihan.show', $exercise->slug) }}" target="_blank" 
                                       class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 px-2.5 py-1.5 rounded-lg hover:bg-emerald-50 transition">
                                        <span>Tinjau</span>
                                        <i data-lucide="play" class="w-3 h-3"></i>
                                    </a>
                                </div>

                                <!-- Modal Edit Soal -->
                                <div id="modal-edit-{{ $exercise->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 text-left">
                                    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-lg w-full p-6 overflow-hidden">
                                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                                            <div>
                                                <h3 class="text-base font-bold text-slate-900">Edit Pengaturan Latihan</h3>
                                                <p class="text-xs text-slate-400">Atur urutan, slug rute, dan batasan eksekusi autograder.</p>
                                            </div>
                                            <button type="button" 
                                                    onclick="document.getElementById('modal-edit-{{ $exercise->id }}').classList.add('hidden')"
                                                    class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100">
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                        </div>

                                        <form method="POST" action="{{ route('admin.latihan.update', $exercise->id) }}">
                                            @csrf
                                            @method('PUT')

                                            <div class="space-y-4 text-xs">
                                                <div>
                                                    <label class="block font-bold text-slate-700 mb-1">Judul Latihan</label>
                                                    <input type="text" name="title" value="{{ old('title', $exercise->title) }}" required
                                                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none">
                                                </div>

                                                <div>
                                                    <label class="block font-bold text-slate-700 mb-1">Slug URL (Rute Navigasi)</label>
                                                    <input type="text" name="slug" value="{{ old('slug', $exercise->slug) }}" required
                                                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm font-mono focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none">
                                                    <p class="text-[11px] text-slate-400 mt-1">Digunakan untuk URL: /latihan/<strong>slug</strong>. Wajib unik tanpa spasi.</p>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block font-bold text-slate-700 mb-1">Tingkat Kesulitan</label>
                                                        <select name="difficulty" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:border-emerald-500 outline-none bg-white">
                                                            <option value="easy" {{ $exercise->difficulty === 'easy' ? 'selected' : '' }}>Pemula (Easy)</option>
                                                            <option value="medium" {{ $exercise->difficulty === 'medium' ? 'selected' : '' }}>Menengah (Medium)</option>
                                                            <option value="hard" {{ $exercise->difficulty === 'hard' ? 'selected' : '' }}>Sulit (Hard)</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block font-bold text-slate-700 mb-1">Urutan Tampil (Sort Order)</label>
                                                        <input type="number" name="sort_order" value="{{ old('sort_order', $exercise->sort_order) }}" min="1" required
                                                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm focus:border-emerald-500 outline-none">
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block font-bold text-slate-700 mb-1">Memori Tujuan (MB)</label>
                                                        <input type="number" name="memory_limit_mb" value="{{ old('memory_limit_mb', $exercise->memory_limit_mb ?? 128) }}" min="16" max="1024" required
                                                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm font-mono focus:border-emerald-500 outline-none">
                                                        <p class="text-[10px] text-slate-400 mt-0.5">Batas RAM Judge0 (standar 128MB)</p>
                                                    </div>

                                                    <div>
                                                        <label class="block font-bold text-slate-700 mb-1">Batas Waktu (Milidetik)</label>
                                                        <input type="number" name="time_limit_ms" value="{{ old('time_limit_ms', $exercise->time_limit_ms ?? 10000) }}" min="500" max="30000" step="500" required
                                                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm font-mono focus:border-emerald-500 outline-none">
                                                        <p class="text-[10px] text-slate-400 mt-0.5">Misal 10000 = 10 detik</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                                                <button type="button" 
                                                        onclick="document.getElementById('modal-edit-{{ $exercise->id }}').classList.add('hidden')"
                                                        class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 font-semibold text-xs transition">
                                                    Batal
                                                </button>
                                                <button type="submit" 
                                                        class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-2xs shadow-emerald-600/20">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 px-6 text-center text-slate-400 text-sm">
                                Belum ada latihan coding. Jalankan seeder latihan terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
