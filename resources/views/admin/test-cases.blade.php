@php
    $visibleCount = $testCases->where('is_hidden', false)->count();
    $hiddenCount = $testCases->where('is_hidden', true)->count();
@endphp

<x-layouts.admin :title="'Test Case - '.$exercise->title" active="latihan">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs text-slate-400 mb-5">
        <a href="{{ route('admin.latihan') }}" class="hover:text-emerald-600 font-semibold">Latihan &amp; Soal</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-700 font-semibold">{{ $exercise->title }}</span>
    </nav>

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">Test Case: {{ $exercise->title }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                {{ $testCases->count() }} test case ({{ $visibleCount }} tampil sebagai contoh, {{ $hiddenCount }} tersembunyi).
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.latihan') }}"
               class="inline-flex items-center gap-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm px-4 py-2.5 rounded-xl transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
            <a href="{{ route('admin.latihan.test-cases.create', $exercise) }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Test Case
            </a>
        </div>
    </div>

    @if ($testCases->isEmpty())
        <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl px-5 py-4 text-sm">
            Soal ini belum punya test case, jadi jawaban siswa belum bisa dinilai otomatis dan bagian "Contoh Uji Coba" kosong.
        </div>
    @elseif ($visibleCount === 0)
        <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl px-5 py-4 text-sm">
            Semua test case tersembunyi, sehingga siswa tidak melihat "Contoh Uji Coba". Sebaiknya sediakan minimal satu test case yang tampil.
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5 w-16">#</th>
                        <th class="px-5 py-3.5">Input</th>
                        <th class="px-5 py-3.5">Output yang diharapkan</th>
                        <th class="px-5 py-3.5 text-center w-32">Tampilan</th>
                        <th class="px-5 py-3.5 text-center w-20">Bobot</th>
                        <th class="px-5 py-3.5 text-right w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($testCases as $testCase)
                        <tr class="hover:bg-slate-50/60 transition align-top">
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
                            <td class="px-5 py-4 text-center">
                                @if ($testCase->is_hidden)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">Tersembunyi</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Contoh</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center font-semibold text-slate-700">{{ $testCase->weight }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.test-cases.edit', $testCase) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg transition">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.test-cases.destroy', $testCase) }}"
                                          onsubmit="return confirm('Hapus test case ini? Hasil pengerjaan siswa untuk test case ini ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Test Case" aria-label="Hapus test case {{ $testCase->sort_order }}">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
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