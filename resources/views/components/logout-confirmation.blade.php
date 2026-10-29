<dialog id="logout-confirmation"
        aria-labelledby="logout-confirmation-title"
        aria-describedby="logout-confirmation-description"
        class="fixed inset-0 m-auto max-h-[calc(100%-2rem)] w-[calc(100%-2rem)] max-w-md overflow-y-auto rounded-3xl border border-slate-200 bg-white p-0 text-slate-800 shadow-2xl backdrop:bg-slate-950/60">
    <div class="p-6 sm:p-7">
        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600">
            <i data-lucide="log-out" class="h-6 w-6"></i>
        </div>
        <h2 id="logout-confirmation-title" class="text-xl font-bold text-slate-900">Keluar dari akun?</h2>
        <p id="logout-confirmation-description" class="mt-2 text-sm leading-6 text-slate-600">
            Kamu perlu masuk kembali untuk melanjutkan belajar.
        </p>
        <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <button type="button" data-logout-cancel autofocus
                    class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Batal
            </button>
            <button type="button" data-logout-confirm
                    class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                Ya, Keluar
            </button>
        </div>
    </div>
</dialog>
