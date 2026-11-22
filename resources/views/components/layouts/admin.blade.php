@props(['title' => 'Admin PyBeginner', 'active' => 'dashboard'])
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - PyBeginner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('styles')
</head>
<body class="h-full text-slate-800 antialiased font-sans">
    <div class="min-h-full flex flex-col md:flex-row">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 bg-white border-r border-slate-200/80 flex flex-col md:h-screen md:sticky md:top-0 shrink-0 overflow-y-hidden">
            <!-- Brand / Logo -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-start shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-start hover:opacity-90 transition">
                    <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-8 w-auto">
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="px-3.5 py-4 flex-1 flex flex-col justify-between overflow-hidden">
                <div>
                    <div class="px-3 mb-2.5 text-[10px] font-bold text-slate-400 tracking-wider uppercase">
                        MENU NAVIGASI
                    </div>

                    <nav class="space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ $active === 'dashboard' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                            <i data-lucide="layout-grid" class="w-4 h-4 shrink-0"></i>
                            <span>Dashboard</span>
                        </a>

                        <!-- Materi Belajar -->
                        <a href="{{ route('admin.materi') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ $active === 'materi' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                            <i data-lucide="book-open" class="w-4 h-4 shrink-0"></i>
                            <span>Materi Belajar</span>
                        </a>

                        <!-- Latihan & Soal -->
                        <a href="{{ route('admin.latihan') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ $active === 'latihan' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                            <i data-lucide="code" class="w-4 h-4 shrink-0"></i>
                            <span>Latihan &amp; Soal</span>
                        </a>

                        <!-- Siswa -->
                        <a href="{{ route('admin.siswa') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ $active === 'siswa' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                            <i data-lucide="users" class="w-4 h-4 shrink-0"></i>
                            <span>Siswa</span>
                        </a>

                        <!-- Penilaian -->
                        <a href="{{ route('admin.penilaian') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ $active === 'penilaian' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                            <i data-lucide="check-square" class="w-4 h-4 shrink-0"></i>
                            <span>Penilaian</span>
                        </a>

                        <!-- Pengaturan -->
                        <a href="{{ route('admin.pengaturan') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition {{ $active === 'pengaturan' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                            <i data-lucide="settings" class="w-4 h-4 shrink-0"></i>
                            <span>Pengaturan</span>
                        </a>
                    </nav>
                </div>

                <!-- Admin Profile Card in Sidebar -->
                <div class="pt-3 border-t border-slate-100 shrink-0">
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-3">
                        <div class="flex items-center gap-2.5 mb-2.5">
                            <div class="w-8 h-8 rounded-full bg-emerald-800 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold text-slate-800 truncate uppercase tracking-tight">
                                    {{ Auth::user()->name ?? 'ADMIN' }}
                                </div>
                                <div class="text-[10px] text-slate-400 truncate leading-tight">
                                    Administrator
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-white hover:bg-red-50 text-red-500 hover:text-red-600 border border-red-100 text-xs font-semibold py-1.5 px-3 rounded-lg flex items-center justify-center gap-1.5 transition shadow-2xs">
                                <i data-lucide="log-out" class="w-3.5 h-3.5 text-red-500"></i>
                                <span>Keluar Akun</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 bg-[#F8FAFC]">
            <!-- Topbar -->
            <header class="h-16 px-6 lg:px-10 border-b border-slate-200/70 bg-white/70 backdrop-blur-sm flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span class="hidden md:inline font-medium">Panel Administrasi</span>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Link Lihat Website -->
                    <a href="{{ route('beranda') }}" target="_blank" 
                       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border border-emerald-200 bg-emerald-50/70 hover:bg-emerald-100 text-emerald-800 text-xs font-bold transition shadow-2xs">
                        <i data-lucide="globe" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Lihat Website</span>
                        <i data-lucide="external-link" class="w-3 h-3 text-emerald-500"></i>
                    </a>

                    <!-- Notification Bell -->
                    <button type="button" class="relative p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100 transition" title="Notifikasi">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-amber-400 rounded-full ring-2 ring-white"></span>
                    </button>

                    <!-- Admin Profile Capsule -->
                    <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border border-slate-200 text-slate-700 text-sm font-semibold shadow-2xs">
                        <div class="w-6 h-6 rounded-full bg-emerald-800 text-white flex items-center justify-center shrink-0">
                            <i data-lucide="user" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="text-xs">Admin</span>
                    </div>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 p-6 lg:p-10 max-w-7xl w-full">
                @if (session('status'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <i data-lucide="alert-circle" class="w-4 h-4 text-red-500 shrink-0"></i>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
    <script>
        if (window.lucide) {
            lucide.createIcons();
        }
    </script>
</body>
</html>
