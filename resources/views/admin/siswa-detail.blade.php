<x-layouts.admin title="Detail Siswa - Admin" active="siswa">
    <a href="{{ route('admin.siswa') }}" class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
        <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Data Siswa
    </a>

    <section class="mb-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-xs">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
            @if ($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="" class="h-16 w-16 rounded-full object-cover">
            @else
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-2xl font-bold text-emerald-700">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Profil Siswa #{{ $user->id }}</p>
                <h1 class="mt-1 text-2xl font-black text-slate-900">{{ $user->name }}</h1>
                <p class="mt-1 break-all text-sm text-slate-500">{{ $user->email }}</p>
                <p class="mt-2 text-xs text-slate-400">
                    Bergabung {{ $user->created_at?->format('d M Y') ?? '-' }} ·
                    {{ $user->google_id ? 'Google' : 'Email & sandi' }} ·
                    {{ $user->is_suspended ? 'Akun dinonaktifkan' : 'Akun aktif' }}
                </p>
            </div>
            <form method="POST" action="{{ route('admin.siswa.suspension', $user) }}">
                @csrf
                <button type="submit" class="rounded-xl border px-4 py-2 text-sm font-semibold {{ $user->is_suspended ? 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' : 'border-amber-200 text-amber-700 hover:bg-amber-50' }}">
                    {{ $user->is_suspended ? 'Aktifkan akun' : 'Nonaktifkan akun' }}
                </button>
            </form>
        </div>
    </section>

    <section class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-xs">
            <p class="text-sm text-slate-500">Materi selesai</p>
            <p class="mt-2 text-2xl font-black text-slate-900">{{ $completedLessons }}<span class="text-base font-semibold text-slate-400"> / {{ $totalLessons }}</span></p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-xs">
            <p class="text-sm text-slate-500">Total submission</p>
            <p class="mt-2 text-2xl font-black text-slate-900">{{ $submissionStats->total ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-xs">
            <p class="text-sm text-slate-500">Rata-rata skor</p>
            <p class="mt-2 text-2xl font-black text-slate-900">{{ number_format((float) ($submissionStats->average_score ?? 0), 1) }}</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-xs">
            <p class="text-sm text-slate-500">Skor sempurna</p>
            <p class="mt-2 text-2xl font-black text-emerald-700">{{ $submissionStats->perfect_scores ?? 0 }}</p>
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xs">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="font-bold text-slate-900">Riwayat latihan terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
                    <tr><th class="px-5 py-3">Latihan</th><th class="px-5 py-3 text-center">Skor</th><th class="px-5 py-3 text-center">Status</th><th class="px-5 py-3 text-right">Waktu</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentSubmissions as $submission)
                        <tr>
                            <td class="px-5 py-4 font-semibold text-slate-800">{{ $submission->exercise?->title ?? 'Latihan dihapus' }}</td>
                            <td class="px-5 py-4 text-center">{{ $submission->score }}</td>
                            <td class="px-5 py-4 text-center">{{ ucfirst($submission->status) }}</td>
                            <td class="px-5 py-4 text-right text-xs text-slate-500">{{ $submission->submitted_at?->diffForHumans() ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat latihan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.admin>
