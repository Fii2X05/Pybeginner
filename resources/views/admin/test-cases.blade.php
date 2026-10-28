@php
    $activeCases = $testCases->where('is_active', true);
    $visibleCount = $activeCases->where('is_hidden', false)->count();
    $hiddenCount = $activeCases->where('is_hidden', true)->count();
    $inactiveCount = $testCases->where('is_active', false)->count();
@endphp

<x-layouts.admin :title="'Test Case - '.$exercise->title" active="latihan">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs text-slate-400 mb-5">
        <a href="{{ route('admin.latihan') }}" class="hover:text-emerald-600 font-semibold">Latihan &amp; Soal</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-700 font-semibold">{{ $exercise->title }}</span>
    </nav>

    {{-- Header --}}
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">Test Case: {{ $exercise->title }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                {{ $activeCases->count() }} aktif ({{ $visibleCount }} tampil sebagai contoh, {{ $hiddenCount }} tersembunyi)@if ($inactiveCount > 0), {{ $inactiveCount }} nonaktif @endif.
                Status soal: <span class="font-semibold {{ $exercise->is_published ? 'text-emerald-700' : 'text-slate-600' }}">{{ $exercise->is_published ? 'Terbit' : 'Draft' }}</span>.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.latihan') }}"
               class="inline-flex items-center gap-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm px-4 py-2.5 rounded-xl transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
            <a href="{{ route('admin.latihan.answer-key', $exercise) }}"
               class="inline-flex items-center gap-2 border border-violet-200 bg-violet-50 hover:bg-violet-100 text-violet-800 font-semibold text-sm px-4 py-2.5 rounded-xl transition">
                <i data-lucide="flask-conical" class="w-4 h-4"></i> Uji Kunci Jawaban
            </a>
            <a href="{{ route('admin.latihan.test-cases.create', $exercise) }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Test Case
            </a>
        </div>
    </div>

    {{-- Status soal dan pilihan simpan --}}
    @php
        $statusBox = match (true) {
            $exercise->is_published && ! $canPublish => 'bg-rose-50 border-rose-200 text-rose-800',
            $exercise->is_published => 'bg-emerald-50 border-emerald-200 text-emerald-800',
            ! $canPublish => 'bg-amber-50 border-amber-200 text-amber-800',
            default => 'bg-emerald-50 border-emerald-200 text-emerald-800',
        };
    @endphp
    <div class="mb-6 rounded-2xl px-5 py-4 text-sm border flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 {{ $statusBox }}">
        <span>
            @if ($exercise->is_published && ! $canPublish)
                Soal ini sedang terbit, tetapi tidak punya test case aktif yang terlihat sehingga siswa tidak bisa mengirim jawaban. Tambahkan test case terlihat, atau tarik soal ke draft.
            @elseif ($exercise->is_published)
                Soal ini sudah terbit dan tampil untuk siswa.
            @elseif (! $canPublish)
                Soal belum bisa diterbitkan sampai punya minimal satu test case aktif yang terlihat oleh siswa.
            @else
                Soal sudah siap diterbitkan. Sebaiknya jalankan <b>Uji Kunci Jawaban</b> dulu untuk memastikan output yang diharapkan benar.
            @endif
        </span>

        <div class="flex flex-wrap items-center gap-2">
            @if ($exercise->is_published)
                <form method="POST" action="{{ route('admin.latihan.status.update', $exercise) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="is_published" value="0">
                    <button type="submit" class="inline-flex items-center gap-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm px-4 py-2 rounded-xl transition whitespace-nowrap">
                        <i data-lucide="eye-off" class="w-4 h-4"></i> Tarik ke Draft
                    </button>
                </form>
            @else
                <a href="{{ route('admin.latihan') }}"
                   class="inline-flex items-center gap-2 border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm px-4 py-2 rounded-xl transition whitespace-nowrap">
                    <i data-lucide="file-edit" class="w-4 h-4"></i> Simpan sebagai Draft
                </a>
                @if ($canPublish)
                    <form method="POST" action="{{ route('admin.latihan.status.update', $exercise) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_published" value="1">
                        <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-4 py-2 rounded-xl transition whitespace-nowrap">
                            <i data-lucide="rocket" class="w-4 h-4"></i> Terbitkan Soal
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5 w-16">#</th>
                        <th class="px-5 py-3.5">Input / Assertion</th>
                        <th class="px-5 py-3.5">Output yang diharapkan</th>
                        <th class="px-5 py-3.5 text-center whitespace-nowrap">Tampilan</th>
                        <th class="px-5 py-3.5 text-center">Bobot</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($testCases as $testCase)
                        @php $hasResults = $withResults->contains($testCase->id); @endphp
                        <tr class="hover:bg-slate-50/60 transition align-top {{ $testCase->is_active ? '' : 'opacity-60' }}">
                            <td class="px-5 py-4 font-bold text-slate-400">#{{ $testCase->sort_order }}</td>
                            <td class="px-5 py-4">
                                @if ($testCase->assertion_code)
                                    <span class="inline-flex mb-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-violet-50 text-violet-700 border border-violet-100">Assertion</span>
                                    <pre class="font-mono text-xs text-slate-700 whitespace-pre-wrap">{{ \Illuminate\Support\Str::limit($testCase->assertion_code, 160) }}</pre>
                                @else
                                    <pre class="font-mono text-xs text-slate-700 whitespace-pre-wrap">{{ $testCase->input !== null && $testCase->input !== '' ? \Illuminate\Support\Str::limit($testCase->input, 160) : '(tanpa input)' }}</pre>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <pre class="font-mono text-xs text-slate-700 whitespace-pre-wrap">{{ $testCase->expected_output !== '' ? \Illuminate\Support\Str::limit($testCase->expected_output, 160) : '-' }}</pre>
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                @if (! $testCase->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">Nonaktif</span>
                                @elseif ($testCase->is_hidden)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">Tersembunyi</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Contoh</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center font-semibold text-slate-700">{{ $testCase->weight }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                                    <a href="{{ route('admin.test-cases.edit', $testCase) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg transition">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.test-cases.active.update', $testCase) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="is_active" value="{{ $testCase->is_active ? 0 : 1 }}">
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-lg transition">
                                            <i data-lucide="power" class="w-3.5 h-3.5"></i>
                                            {{ $testCase->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    @unless ($hasResults)
                                        <form method="POST" action="{{ route('admin.test-cases.destroy', $testCase) }}"
                                              onsubmit="return confirm('Hapus test case ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus test case" aria-label="Hapus test case {{ $testCase->sort_order }}">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-1 text-[11px] text-slate-400" title="Sudah punya hasil pengerjaan siswa, hanya bisa dinonaktifkan">Ada riwayat</span>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-sm">
                                Belum ada test case. Klik "Tambah Test Case" untuk membuat yang pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>