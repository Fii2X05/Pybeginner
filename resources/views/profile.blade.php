<x-layouts.app title="Profil Saya">
    <div class="mx-auto max-w-3xl">
        <div class="mb-8">
            <div class="mb-1 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700">
                <span>Akun Saya</span><span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 lg:text-3xl">Profil Saya</h1>
            <p class="mt-1 text-sm text-slate-500">Perbarui nama dan foto profil akun Anda.</p>
        </div>

        @if (session('status'))
            <div role="status" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-100 bg-white p-5 shadow-xs sm:p-8">
            @csrf
            @method('PUT')

            <div class="flex flex-col gap-5 border-b border-slate-100 pb-6 sm:flex-row sm:items-center">
                <div class="relative h-24 w-24 shrink-0">
                    <img id="avatar-preview" src="{{ $user->avatar_url ?? '' }}" alt="Pratinjau foto profil" class="{{ $user->avatar_url ? '' : 'hidden' }} h-24 w-24 rounded-full object-cover ring-4 ring-emerald-50">
                    <div id="avatar-fallback" class="{{ $user->avatar_url ? 'hidden' : 'flex' }} h-24 w-24 items-center justify-center rounded-full bg-emerald-100 text-3xl font-bold text-emerald-700 ring-4 ring-emerald-50">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="font-bold text-slate-900">Foto profil</h2>
                    <p class="mt-1 text-sm text-slate-500">Pilih foto JPG, PNG, atau WEBP dengan ukuran maksimal 2 MB.</p>
                    <div class="mt-3 flex flex-wrap items-center gap-3">
                        <label for="avatar" class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 px-3.5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            <i data-lucide="image-plus" class="h-4 w-4"></i> Pilih foto
                        </label>
                        <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only">
                        @if ($user->avatar_url)
                            <label class="inline-flex items-center gap-2 text-sm text-rose-600">
                                <input type="checkbox" name="remove_avatar" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                Hapus foto saat ini
                            </label>
                        @endif
                    </div>
                    @error('avatar')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama lengkap</label>
                <input id="name" name="name" type="text" required maxlength="255" autocomplete="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                @error('name')<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat email</label>
                <input id="email" type="email" value="{{ $user->email }}" readonly class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-500">
                <p class="mt-1.5 text-xs text-slate-400">Email akun tidak dapat diubah dari halaman ini.</p>
            </div>

            <div class="flex justify-end border-t border-slate-100 pt-4">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-700">
                    <i data-lucide="save" class="h-4 w-4"></i> Simpan perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>

@push('scripts')
    <script>
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatar-preview');
        const avatarFallback = document.getElementById('avatar-fallback');

        avatarInput.addEventListener('change', () => {
            const [file] = avatarInput.files;

            if (!file) {
                return;
            }

            avatarPreview.src = URL.createObjectURL(file);
            avatarPreview.classList.remove('hidden');
            avatarFallback.classList.add('hidden');
        });
    </script>
@endpush
