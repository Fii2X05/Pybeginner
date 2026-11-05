<x-layouts.admin title="Test Case - {{ $exercise->title }}" active="latihan">
    <a href="{{ route('admin.latihan') }}" class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Bank Soal
    </a>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Test Case: {{ $exercise->title }}</h1>
            <p class="mt-1 text-sm text-slate-500">Pilih metode evaluasi assertion atau input/output untuk tiap kasus uji.</p>
        </div>
        <a href="{{ route('admin.latihan.test-cases.create', $exercise) }}" class="inline-flex items-center gap-2 self-start rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">
            <i data-lucide="plus" class="h-4 w-4"></i> Tambah Test Case
        </a>
    </div>

    @if ($exercise->is_published && ! $testCases->contains(fn ($testCase) => $testCase->is_active && ! $testCase->is_hidden))
        <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">Latihan ini belum memiliki test case publik aktif; siswa tidak dapat menjalankan contoh uji coba.</div>
    @endif
    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr><th class="px-5 py-3.5">Urutan</th><th class="px-5 py-3.5">Metode</th><th class="px-5 py-3.5">Isi uji</th><th class="px-5 py-3.5 text-center">Bobot</th><th class="px-5 py-3.5 text-center">Visibilitas</th><th class="px-5 py-3.5 text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($testCases as $testCase)
                        <tr>
                            <td class="px-5 py-4 text-slate-500">#{{ $testCase->sort_order }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $testCase->assertion_code ? 'bg-indigo-50 text-indigo-700' : 'bg-sky-50 text-sky-700' }}">{{ $testCase->assertion_code ? 'Assertion' : 'Input / Output' }}</span>
                                <div class="mt-1 text-[11px] text-slate-400">{{ $testCase->results_count }} hasil · {{ $testCase->progress_count }} progres</div>
                            </td>
                            <td class="max-w-md px-5 py-4">
                                <div class="truncate font-mono text-xs text-slate-700">{{ $testCase->assertion_code ?: 'Input: '.($testCase->input ?? '(kosong)').' → Output: '.$testCase->expected_output }}</div>
                            </td>
                            <td class="px-5 py-4 text-center">{{ $testCase->weight }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="rounded-full px-2.5 py-1 text-xs {{ $testCase->is_hidden ? 'bg-slate-100 text-slate-600' : 'bg-emerald-50 text-emerald-700' }}">{{ $testCase->is_hidden ? 'Tersembunyi' : 'Publik' }}</span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.test-cases.edit', $testCase) }}" title="Edit test case" class="rounded-lg p-2 text-blue-600 hover:bg-blue-50"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                                    <form method="POST" action="{{ route('admin.test-cases.destroy', $testCase) }}" onsubmit="return confirm('Hapus test case ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" @disabled($testCase->results_count || $testCase->progress_count) title="{{ $testCase->results_count || $testCase->progress_count ? 'Test case sudah digunakan siswa' : 'Hapus test case' }}" class="rounded-lg p-2 text-rose-600 hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-30">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">Belum ada test case. Tambahkan minimal satu test case publik sebelum menerbitkan latihan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
