<x-layouts.admin title="Kelola Pengguna - Admin" active="siswa">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700">
                <span>Manajemen Pengguna</span>
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 lg:text-3xl">Kelola Pengguna</h1>
            <p class="mt-1 text-sm text-slate-500">Cari dan kelola akun learner, mentor, serta admin.</p>
        </div>
        <span class="w-fit rounded-xl bg-emerald-100/70 px-3.5 py-1.5 text-xs font-bold text-emerald-800">
            {{ $users->total() }} Pengguna
        </span>
    </div>

    @if (session('status'))
        <div role="status" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="GET" action="{{ route('admin.siswa') }}" class="mb-5 grid gap-3 rounded-2xl border border-slate-100 bg-white p-4 shadow-xs md:grid-cols-[minmax(12rem,1fr)_repeat(4,minmax(8rem,11rem))_auto]">
        <label class="sr-only" for="user-search">Cari nama atau email</label>
        <input id="user-search" name="search" value="{{ $filters['search'] ?? '' }}" type="search" placeholder="Cari nama atau email..." class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">

        <label class="sr-only" for="user-role">Filter role pengguna</label>
        <select id="user-role" name="role" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
            <option value="">Semua role</option>
            <option value="learner" @selected(($filters['role'] ?? '') === 'learner')>Learner</option>
            <option value="mentor" @selected(($filters['role'] ?? '') === 'mentor')>Mentor</option>
            <option value="admin" @selected(($filters['role'] ?? '') === 'admin')>Admin</option>
        </select>

        <label class="sr-only" for="user-registration">Metode registrasi</label>
        <select id="user-registration" name="registration" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
            <option value="">Semua metode</option>
            <option value="google" @selected(($filters['registration'] ?? '') === 'google')>Google</option>
            <option value="email" @selected(($filters['registration'] ?? '') === 'email')>Email &amp; sandi</option>
        </select>

        <label class="sr-only" for="user-status">Status akun</label>
        <select id="user-status" name="status" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
            <option value="">Semua status</option>
            <option value="active" @selected(($filters['status'] ?? '') === 'active')>Aktif</option>
            <option value="suspended" @selected(($filters['status'] ?? '') === 'suspended')>Dinonaktifkan</option>
        </select>

        <label class="sr-only" for="user-sort">Urutan tanggal bergabung</label>
        <select id="user-sort" name="joined" class="rounded-xl border border-slate-200 px-3 py-2 text-sm">
            <option value="newest" @selected(($filters['joined'] ?? 'newest') === 'newest')>Terbaru bergabung</option>
            <option value="oldest" @selected(($filters['joined'] ?? '') === 'oldest')>Terlama bergabung</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-emerald-700">Terapkan</button>
            <a href="{{ route('admin.siswa') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3.5">Pengguna</th>
                        <th class="px-5 py-3.5">Email</th>
                        <th class="px-5 py-3.5 text-center">Registrasi</th>
                        <th class="px-5 py-3.5 text-center">Peran</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Bergabung</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="align-top transition hover:bg-slate-50/50">
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($user->avatar_url)
                                        <img src="{{ $user->avatar_url }}" alt="" class="h-9 w-9 rounded-full object-cover">
                                    @else
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        @if ($user->role === 'learner')
                                            <a href="{{ route('admin.siswa.show', $user) }}" class="font-bold text-slate-900 hover:text-emerald-700">{{ $user->name }}</a>
                                        @else
                                            <span class="font-bold text-slate-900">{{ $user->name }}</span>
                                        @endif
                                        <div class="text-[11px] text-slate-400">ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-mono text-xs text-slate-600">{{ $user->email }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-center">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $user->google_id ? 'border border-red-100 bg-red-50 text-red-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $user->google_id ? 'Google' : 'Email & sandi' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if ($user->role !== 'admin' && $user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.siswa.role', $user) }}" class="flex min-w-40 gap-1">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" aria-label="Peran {{ $user->name }}" class="min-w-0 flex-1 rounded-lg border border-slate-200 px-2 py-1.5 text-xs">
                                            <option value="learner" @selected($user->role === 'learner')>Learner</option>
                                            <option value="mentor" @selected($user->role === 'mentor')>Mentor</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                        <button type="submit" title="Simpan peran" class="rounded-lg px-2 text-emerald-700 hover:bg-emerald-50">
                                            <i data-lucide="save" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs font-semibold text-slate-500">{{ ucfirst($user->role) }} <span class="text-slate-400">(dilindungi)</span></span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-center">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->is_suspended ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $user->is_suspended ? 'Dinonaktifkan' : 'Aktif' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right text-xs text-slate-500">
                                {{ $user->created_at?->format('d M Y') ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex justify-end gap-1">
                                    @if ($user->role === 'learner')
                                        <a href="{{ route('admin.siswa.show', $user) }}" title="Lihat aktivitas belajar" class="rounded-lg p-2 text-slate-500 hover:bg-emerald-50 hover:text-emerald-700">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                    @endif
                                    @if ($user->role !== 'admin' && $user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.siswa.suspension', $user) }}">
                                            @csrf
                                            <button type="submit" title="{{ $user->is_suspended ? 'Aktifkan akun' : 'Nonaktifkan akun' }}" class="rounded-lg p-2 text-amber-600 hover:bg-amber-50">
                                                <i data-lucide="{{ $user->is_suspended ? 'play' : 'pause' }}" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                        <button type="button" title="Hapus akun" onclick="document.getElementById('delete-user-{{ $user->id }}').showModal()" class="rounded-lg p-2 text-rose-600 hover:bg-rose-50">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    @else
                                        <span class="px-2 text-xs text-slate-400">—</span>
                                    @endif
                                </div>
                                @if ($user->role !== 'admin' && $user->id !== auth()->id())
                                <dialog id="delete-user-{{ $user->id }}" class="w-[calc(100%-2rem)] max-w-md rounded-2xl p-0 shadow-2xl backdrop:bg-slate-900/50">
                                    <form method="POST" action="{{ route('admin.siswa.destroy', $user) }}" onsubmit="return confirm('Konfirmasi terakhir: hapus akun dan seluruh riwayat belajar pengguna ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <div class="p-6">
                                            <h2 class="text-lg font-black text-slate-900">Hapus akun pengguna?</h2>
                                            <p class="mt-2 text-sm leading-6 text-slate-600">Tindakan ini permanen dan menghapus seluruh riwayat belajar. Ketik nama <strong>{{ $user->name }}</strong> untuk melanjutkan.</p>
                                            <input name="confirm_name" required autocomplete="off" class="mt-4 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm" placeholder="Nama lengkap pengguna">
                                            <div class="mt-5 flex justify-end gap-2">
                                                <button type="button" onclick="this.closest('dialog').close()" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600">Batal</button>
                                                <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-bold text-white hover:bg-rose-700">Hapus permanen</button>
                                            </div>
                                        </div>
                                    </form>
                                </dialog>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-10 text-center text-sm text-slate-400">Tidak ada pengguna yang cocok dengan pencarian atau filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="border-t border-slate-100 p-4">{{ $users->appends($filters)->links() }}</div>
        @endif
    </div>
</x-layouts.admin>
