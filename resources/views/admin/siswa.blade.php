<x-layouts.admin title="Data Siswa - Admin" active="siswa">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
                <span>MANAJEMEN PENGGUNA</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Data Siswa
            </h1>
            <p class="text-sm text-slate-500 mt-1">Daftar siswa pembelajar aktif yang terdaftar di platform PyBeginner.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3.5 py-1.5 rounded-xl bg-emerald-100/70 text-emerald-800 text-xs font-bold">
                Total {{ $students->total() }} Siswa
            </span>
        </div>
    </div>

    <!-- Table of Students -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Siswa</th>
                        <th class="py-3.5 px-6">Email</th>
                        <th class="py-3.5 px-6 text-center">Metode Registrasi</th>
                        <th class="py-3.5 px-6 text-center">Status Role</th>
                        <th class="py-3.5 px-6 text-right">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    @if ($student->avatar)
                                        <img src="{{ $student->avatar }}" alt="{{ $student->name }}" class="w-9 h-9 rounded-full object-cover">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $student->name }}</div>
                                        <div class="text-[11px] text-slate-400">ID: #{{ $student->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600 font-mono text-xs">
                                {{ $student->email }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if ($student->google_id)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                        <i data-lucide="chrome" class="w-3 h-3"></i> Google
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        <i data-lucide="mail" class="w-3 h-3"></i> Email &amp; Sandi
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    Learner
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right text-xs text-slate-500">
                                {{ $student->created_at ? $student->created_at->format('d M Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 px-6 text-center text-slate-400 text-sm">
                                Belum ada siswa terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
