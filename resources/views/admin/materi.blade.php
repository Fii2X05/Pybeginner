<x-layouts.admin title="Materi Belajar - Admin" active="materi">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700">
                <span>Manajemen Konten</span><span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 lg:text-3xl">Materi Belajar</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola modul kurikulum dan materi pelajaran siswa.</p>
        </div>
        <a href="{{ route('admin.materi.create') }}" class="inline-flex items-center gap-2 self-start rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">
            <i data-lucide="plus" class="h-4 w-4"></i> Tambah Modul
        </a>
    </div>

    @if ($errors->any())
        <div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    @if ($courses->isEmpty())
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800">Belum ada kursus. Modul harus dibuat di dalam kursus; tambahkan kursus terlebih dahulu.</div>
    @else
        <div class="mb-4 text-sm text-slate-500">{{ $modules->count() }} modul terdaftar</div>
        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-bold uppercase tracking-wider text-slate-500">
                            <th class="px-5 py-3.5">Urutan</th><th class="px-5 py-3.5">Modul</th><th class="px-5 py-3.5">Kursus</th>
                            <th class="px-5 py-3.5">Slug</th><th class="px-5 py-3.5 text-center">Pelajaran</th><th class="px-5 py-3.5 text-center">Status</th><th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($modules as $module)
                            <tr class="hover:bg-slate-50/50">
                                <td class="whitespace-nowrap px-5 py-4 font-bold text-slate-400">#{{ sprintf('%02d', $module->sort_order) }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-900">{{ $module->title }}</div>
                                    <div class="mt-0.5 max-w-sm truncate text-xs text-slate-400">{{ $module->description }}</div>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">{{ $module->course?->title ?? '-' }}</td>
                                <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $module->slug }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">{{ $module->lessons_count }} pelajaran</span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $module->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $module->is_published ? 'Aktif' : 'Draft' }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.materi.lessons', $module) }}" title="Kelola pelajaran" class="rounded-lg p-2 text-emerald-700 hover:bg-emerald-50"><i data-lucide="book-open" class="h-4 w-4"></i></a>
                                        <a href="{{ route('admin.materi.edit', $module) }}" title="Edit modul" class="rounded-lg p-2 text-blue-600 hover:bg-blue-50"><i data-lucide="pencil" class="h-4 w-4"></i></a>
                                        @if ($module->is_published)
                                            <a href="{{ route('modul.index') }}" target="_blank" rel="noopener" title="Pratinjau modul" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100"><i data-lucide="external-link" class="h-4 w-4"></i></a>
                                        @endif
                                        <form method="POST" action="{{ route('admin.materi.destroy', $module) }}" onsubmit="return confirm('Hapus modul kosong ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" @disabled($module->lessons_count > 0) title="{{ $module->lessons_count > 0 ? 'Hapus pelajaran terlebih dahulu; riwayat siswa tidak dihapus otomatis' : 'Hapus modul' }}" class="rounded-lg p-2 text-rose-600 hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-30"><i data-lucide="trash-2" class="h-4 w-4"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-5 py-10 text-center text-slate-400">Belum ada modul.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</x-layouts.admin>
