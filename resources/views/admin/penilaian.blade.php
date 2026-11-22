<x-layouts.admin title="Penilaian - Admin" active="penilaian">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 tracking-wider uppercase mb-1">
                <span>EVALUASI &amp; SKOR</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Penilaian Siswa
            </h1>
            <p class="text-sm text-slate-500 mt-1">Riwayat submission kode pemrograman dan hasil evaluasi autograder latihan siswa.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3.5 py-1.5 rounded-xl bg-emerald-100/70 text-emerald-800 text-xs font-bold">
                Total {{ $submissions->total() }} Submission
            </span>
        </div>
    </div>

    <!-- Table of Submissions -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Siswa</th>
                        <th class="py-3.5 px-6">Latihan / Soal</th>
                        <th class="py-3.5 px-6 text-center">Skor</th>
                        <th class="py-3.5 px-6 text-center">Test Passed</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Dikumpulkan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($submissions as $submission)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $submission->user->name ?? 'Siswa' }}</div>
                                <div class="text-xs text-slate-400">{{ $submission->user->email ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800">{{ $submission->exercise->title ?? 'Latihan' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $submission->exercise->slug ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-base">
                                <span class="{{ ($submission->score ?? 0) >= 70 ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $submission->score ?? 0 }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center text-xs font-mono text-slate-600">
                                {{ $submission->passed_tests ?? 0 }} / {{ $submission->total_tests ?? 0 }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if (strtolower($submission->status) === 'accepted' || strtolower($submission->status) === 'passed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Lolos
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                        Belum Lolos
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right text-xs text-slate-500">
                                {{ $submission->submitted_at ? $submission->submitted_at->diffForHumans() : ($submission->created_at ? $submission->created_at->diffForHumans() : '-') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 px-6 text-center text-slate-400 text-sm">
                                Belum ada riwayat submission siswa. Siswa yang mengerjakan latihan akan tercatat di sini secara otomatis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($submissions->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
