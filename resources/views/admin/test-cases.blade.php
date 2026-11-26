<x-layouts.admin title="Test Case - {{ $exercise->title }}" active="latihan">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.latihan') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-emerald-700 mb-2">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Kembali ke Daftar Latihan
            </a>
            <h1 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Test Case &middot; {{ $exercise->title }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Kode siswa dijalankan terhadap semua test case aktif. Yang <strong>Tersembunyi</strong> tetap dipakai menilai tanpa diperlihatkan ke siswa.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.latihan.answer-key', $exercise) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition shadow-2xs">
                <i data-lucide="play" class="w-4 h-4 text-emerald-600"></i>
                Uji Kunci Jawaban
            </a>
            <a href="{{ route('admin.latihan.test-cases.create', $exercise) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-2xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Test Case
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-sm font-medium">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Status terbit soal & tombol langsung --}}
    <div class="mb-6 p-4 rounded-2xl border flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 {{ $exercise->is_published ? 'bg-emerald-50/70 border-emerald-200' : 'bg-amber-50/70 border-amber-200' }}">
        <div class="text-xs leading-relaxed">
            @if ($exercise->is_published)
                <span class="font-bold text-emerald-900">Status Soal: Terbit</span>
                <span class="text-emerald-800"> — siswa sudah dapat mengerjakan latihan ini.</span>
            @elseif ($canPublish)
                <span class="font-bold text-amber-900">Status Soal: Draft (Siap Terbit)</span>
                <span class="text-amber-800"> — test case sudah lengkap. Terbitkan sekarang atau uji kunci jawabannya terlebih dahulu.</span>
            @else
                <span class="font-bold text-amber-900">Status Soal: Draft</span>
                <span class="text-amber-800"> — tambahkan minimal satu test case aktif yang terlihat oleh siswa agar soal ini bisa diterbitkan.</span>
            @endif
        </div>
        <form method="POST" action="{{ route('admin.latihan.status.update', $exercise) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="is_published" value="{{ $exercise->is_published ? 0 : 1 }}">
            <button type="submit" @disabled(! $exercise->is_published && ! $canPublish)
                    class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $exercise->is_published ? 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' : 'bg-emerald-600 hover:bg-emerald-700 text-white disabled:opacity-40 disabled:cursor-not-allowed' }}">
                {{ $exercise->is_published ? 'Tarik ke Draft' : 'Terbitkan Soal' }}
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center">No</th>
                        <th class="py-3.5 px-4">Jenis</th>
                        <th class="py-3.5 px-4">Input / Pengujian</th>
                        <th class="py-3.5 px-4">Output Diharapkan</th>
                        <th class="py-3.5 px-4 text-center">Bobot</th>
                        <th class="py-3.5 px-4 text-center">Tampilan</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($testCases as $tc)
                        @php
                            $isAssertion = is_string($tc->assertion_code) && trim($tc->assertion_code) !== '';
                            $hasResults = $withResults->contains($tc->id);
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition {{ $tc->is_active ? '' : 'opacity-60' }}">
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-100 text-xs font-bold text-slate-500">
                                    {{ $tc->sort_order }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4">
                                @if ($isAssertion)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 text-indigo-700">Assertion</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700">Input / Output</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 max-w-xs">
                                @if ($isAssertion)
                                    <pre class="text-xs font-mono text-slate-700 bg-slate-50 border border-slate-200/80 rounded-lg px-2.5 py-1.5 overflow-x-auto max-h-24 whitespace-pre-wrap">{{ $tc->assertion_code }}</pre>
                                @elseif ($tc->input !== null && $tc->input !== '')
                                    <pre class="text-xs font-mono text-slate-700 bg-slate-50 border border-slate-200/80 rounded-lg px-2.5 py-1.5 overflow-x-auto max-h-24 whitespace-pre-wrap">{{ $tc->input }}</pre>
                                @else
                                    <span class="text-xs text-slate-400 italic">Tanpa input</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 max-w-xs">
                                @if ($isAssertion)
                                    <span class="text-xs text-slate-400 italic">Diperiksa oleh assertion</span>
                                @else
                                    <pre class="text-xs font-mono text-slate-700 bg-slate-50 border border-slate-200/80 rounded-lg px-2.5 py-1.5 overflow-x-auto max-h-24 whitespace-pre-wrap">{{ $tc->expected_output }}</pre>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center font-mono text-xs text-slate-700 font-semibold">
                                {{ $tc->weight }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if ($tc->is_hidden)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700">
                                        <i data-lucide="eye-off" class="w-3 h-3"></i>
                                        Tersembunyi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-sky-700">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        Terlihat
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <form method="POST" action="{{ route('admin.test-cases.active.update', $tc) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="is_active" aria-label="Status test case {{ $tc->sort_order }}"
                                            onchange="this.form.submit()"
                                            class="h-8 px-2 rounded-lg border text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 {{ $tc->is_active ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-500' }}">
                                        <option value="1" @selected($tc->is_active)>Aktif</option>
                                        <option value="0" @selected(! $tc->is_active)>Draft</option>
                                    </select>
                                </form>
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.test-cases.edit', $tc) }}" title="Edit test case"
                                       class="w-8 h-8 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 transition">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>

                                    @if ($hasResults)
                                        <span title="Sudah ada hasil pengerjaan siswa; ubah status ke Draft jika tidak lagi dipakai menilai"
                                              class="w-8 h-8 inline-flex items-center justify-center rounded-lg border border-slate-100 bg-slate-50 text-slate-300 cursor-not-allowed">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('admin.test-cases.destroy', $tc) }}"
                                              onsubmit="return confirm('Hapus test case #{{ $tc->sort_order }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus test case"
                                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600 transition">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 px-6 text-center text-slate-400 text-sm">
                                Belum ada test case untuk soal ini. Klik "Tambah Test Case" untuk menambahkan pengujian pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/60 text-xs text-slate-500">
            Total {{ $testCases->count() }} test case &middot; {{ $testCases->where('is_active', true)->count() }} aktif
        </div>
    </div>
</x-layouts.admin>