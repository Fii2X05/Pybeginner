<dialog id="logout-confirmation-modal" aria-labelledby="logout-confirmation-title" class="fixed inset-0 m-auto max-h-[calc(100%-2rem)] w-[calc(100%-2rem)] max-w-md overflow-auto rounded-2xl border border-slate-100 p-0 shadow-2xl backdrop:bg-slate-900/50">
    <div class="p-6">
        <div class="flex items-start gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-rose-50 text-rose-600">
                <i data-lucide="log-out" class="h-5 w-5"></i>
            </div>
            <div>
                <h2 id="logout-confirmation-title" class="text-lg font-black text-slate-900">Keluar dari akun?</h2>
                <p class="mt-1 text-sm leading-6 text-slate-500">Anda perlu masuk kembali untuk mengakses akun.</p>
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" onclick="this.closest('dialog').close()" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                Batal
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-rose-700">
                    Ya, keluar
                </button>
            </form>
        </div>
    </div>
</dialog>
