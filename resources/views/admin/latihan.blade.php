<x-layouts.admin title="Latihan & Soal - Admin" active="latihan">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700">
                <span>Bank Soal</span><span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 lg:text-3xl">Latihan &amp; Soal</h1>
            <p class="mt-1 text-sm text-slate-500">Buat latihan, kelola test case, dan atur soal yang diterbitkan untuk siswa.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="rounded-xl bg-emerald-100/70 px-3.5 py-1.5 text-xs font-bold text-emerald-800">Total {{ $exercises->count() }} Latihan</span>
            <a href="{{ route('admin.latihan.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-700">
                <i data-lucide="plus" class="h-4 w-4"></i> Buat Latihan
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    @if ($exercises->isEmpty())
        <div class="rounded-2xl border border-slate-100 bg-white p-10 text-center">
            <p class="font-semibold text-slate-800">Belum ada latihan.</p>
            <p class="mt-1 text-sm text-slate-500">Buat soal baru atau pastikan materi pelajaran sudah tersedia.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-bold uppercase tracking-wider text-slate-500">
                            <th class="px-5 py-3.5">Urutan</th><th class="px-5 py-3.5">Judul Latihan</th>
                            <th class="px-5 py-3.5">Modul &amp; Pelajaran</th><th class="px-5 py-3.5 text-center">Kesulitan</th>
                            <th class="px-5 py-3.5 text-center">Test Case</th><th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($exercises as $exercise)
                            <tr class="hover:bg-slate-50/50">
                                <td class="whitespace-nowrap px-5 py-4 font-bold text-slate-400">#{{ sprintf('%02d', $exercise->sort_order) }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-900">{{ $exercise->title }}</div>
                                    <div class="mt-0.5 font-mono text-xs text-slate-400">{{ $exercise->slug }}</div>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    <div class="font-semibold text-slate-800">{{ $exercise->lesson?->module?->title ?? 'Modul tidak tersedia' }}</div>
                                    <div class="text-slate-400">{{ $exercise->lesson?->title ?? 'Pelajaran tidak tersedia' }}</div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-center">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $exercise->difficulty === 'easy' ? 'bg-emerald-50 text-emerald-700' : ($exercise->difficulty === 'medium' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                        {{ ['easy' => 'Mudah', 'medium' => 'Sedang', 'hard' => 'Sulit'][$exercise->difficulty] ?? ucfirst($exercise->difficulty) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-center text-xs text-slate-600">
                                    {{ $exercise->test_cases_count }} aktif
                                    <div class="text-[11px] text-slate-400">{{ $exercise->submissions_count }} submission</div>
                                </td>
                                <td class="px-5 py-4">
                                    <form method="POST" action="{{ route('admin.latihan.status.update', $exercise) }}">
                                        @csrf @method('PUT')
                                        <label class="sr-only" for="exercise-status-{{ $exercise->id }}">Status {{ $exercise->title }}</label>
                                        <select id="exercise-status-{{ $exercise->id }}" name="is_published" onchange="this.form.submit()" class="mx-auto block rounded-full border-0 px-2.5 py-1 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 {{ $exercise->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                            <option value="1" @selected($exercise->is_published)>Terbit</option>
                                            <option value="0" @selected(! $exercise->is_published)>Draft</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.latihan.test-cases', $exercise) }}" title="Kelola test case" class="rounded-lg p-2 text-indigo-600 hover:bg-indigo-50">
                                            <i data-lucide="list-checks" class="h-4 w-4"></i>
                                        </a>
                                        <a href="{{ route('admin.latihan.edit', $exercise) }}" title="Edit latihan" class="rounded-lg p-2 text-blue-600 hover:bg-blue-50">
                                            <i data-lucide="pencil" class="h-4 w-4"></i>
                                        </a>
                                        @if ($exercise->is_published)
                                            <a href="{{ route('latihan.show', $exercise->slug) }}" target="_blank" rel="noopener" title="Pratinjau soal" class="rounded-lg p-2 text-emerald-600 hover:bg-emerald-50">
                                                <i data-lucide="external-link" class="h-4 w-4"></i>
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('admin.latihan.destroy', $exercise) }}" onsubmit="return confirm('Hapus latihan ini? Riwayat siswa yang sudah ada akan dipertahankan dan membuat penghapusan ditolak oleh server.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" @disabled($exercise->submissions_count || $exercise->test_case_progress_count) title="{{ $exercise->submissions_count || $exercise->test_case_progress_count ? 'Tidak dapat dihapus karena sudah digunakan siswa' : 'Hapus latihan' }}" class="rounded-lg p-2 text-rose-600 hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-30">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-layouts.admin>
