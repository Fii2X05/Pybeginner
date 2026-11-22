<x-layouts.app title="Bantuan" active="">
    <section class="mx-auto max-w-2xl rounded-3xl border border-emerald-100 bg-white p-6 shadow-sm sm:p-8">
        <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Pusat Bantuan</p>
        <h1 class="mt-2 text-3xl font-black text-slate-900">Ada yang bisa kami bantu?</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">Kirim laporan bug, pertanyaan kurikulum, atau minta bantuan terkait kode. Tim admin akan menerima notifikasi pesan Anda.</p>

        @if (session('status'))
            <div role="status" class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('support.store') }}" class="mt-6 space-y-5">
            @csrf
            <div>
                <label for="category" class="mb-2 block text-sm font-semibold text-slate-700">Jenis bantuan</label>
                <select id="category" name="category" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                    <option value="bug" @selected(old('category') === 'bug')>Laporan bug</option>
                    <option value="curriculum" @selected(old('category') === 'curriculum')>Pertanyaan kurikulum</option>
                    <option value="code_help" @selected(old('category') === 'code_help')>Bantuan kode</option>
                </select>
            </div>
            <div>
                <label for="message" class="mb-2 block text-sm font-semibold text-slate-700">Pesan</label>
                <textarea id="message" name="message" rows="7" minlength="10" maxlength="5000" required placeholder="Jelaskan masalah atau pertanyaan Anda..." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">{{ old('message') }}</textarea>
                @error('message')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-700">Kirim ke Admin</button>
        </form>
    </section>
</x-layouts.app>
