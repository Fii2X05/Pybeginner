<footer class="border-t border-emerald-100 bg-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-6 py-12 sm:grid-cols-2 lg:grid-cols-4 lg:px-10">
        <div class="lg:col-span-2">
            <a href="{{ route('beranda') }}" class="inline-block">
                <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-9 w-auto">
            </a>
            <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate-600">
                Platform interaktif untuk belajar Python dari nol secara terstruktur dan ramah pemula.
            </p>
        </div>

        <div>
            <h4 class="mb-4 text-sm font-bold text-slate-900">Belajar</h4>
            <ul class="space-y-2 text-sm text-slate-600">
                <li><a href="{{ route('modul.index') }}" class="transition hover:text-emerald-700">Modul Belajar</a></li>
                <li><a href="{{ route('latihan.index') }}" class="transition hover:text-emerald-700">Latihan Coding</a></li>
                <li><a href="{{ route('submissions.history') }}" class="transition hover:text-emerald-700">Riwayat Submission</a></li>
            </ul>
        </div>

        <div>
            <h4 class="mb-4 text-sm font-bold text-slate-900">Fitur</h4>
            <ul class="space-y-2 text-sm text-slate-600">
                <li>Belajar langsung dari browser</li>
                <li>Penilaian kode otomatis</li>
                <li>Feedback instan</li>
            </ul>
        </div>
    </div>

    <div class="border-t border-emerald-50">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-6 py-5 text-xs text-slate-500 sm:flex-row lg:px-10">
            <span>&copy; {{ date('Y') }} Tim PBL Kelompok 5 - SIB 3B</span>
            <span class="hidden font-mono text-emerald-700 md:inline">print("Selamat belajar dan terus berkarya!")</span>
            <img src="{{ asset('images/footer.png') }}" alt="" class="h-14 w-auto">
        </div>
    </div>
</footer>