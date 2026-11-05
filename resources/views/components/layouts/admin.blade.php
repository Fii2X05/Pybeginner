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
        <aside class="w-full md:w-64 bg-white border-r border-slate-200/80 flex flex-col md:min-h-screen md:sticky md:top-0 shrink-0">
            <!-- Brand / Logo -->
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-pybeginner.png') }}" alt="PyBeginner" class="h-9 w-auto">
                    <div>
                        <div class="font-extrabold text-emerald-600 text-base leading-tight tracking-tight">PyBeginner</div>
                        <div class="text-[9px] font-bold text-emerald-700 tracking-wider uppercase">ADMIN WORKSPACE</div>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="px-4 py-6 flex-1 flex flex-col justify-between">
                <div>
                    <div class="px-3 mb-3 text-[11px] font-bold text-slate-400 tracking-wider uppercase">
                        MENU NAVIGASI
                    </div>

                    <nav class="space-y-1.5">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ $active === 'dashboard' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                            <i data-lucide="layout-grid" class="w-4 h-4 shrink-0"></i>
                            <span>Dashboard</span>
                        </a>

                        <!-- Materi Belajar -->
                        <a href="{{ route('admin.materi') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ $active === 'materi' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                            <i data-lucide="book-open" class="w-4 h-4 shrink-0"></i>
                            <span>Materi Belajar</span>
                        </a>

                        <!-- Latihan & Soal -->
                        <a href="{{ route('admin.latihan') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ $active === 'latihan' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                            <i data-lucide="code" class="w-4 h-4 shrink-0"></i>
                            <span>Latihan &amp; Soal</span>
                        </a>

                        <!-- Pengguna -->
                        <a href="{{ route('admin.siswa') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ $active === 'siswa' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                            <i data-lucide="users" class="w-4 h-4 shrink-0"></i>
                            <span>Pengguna</span>
                        </a>

                        <!-- Penilaian -->
                        <a href="{{ route('admin.penilaian') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ $active === 'penilaian' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                            <i data-lucide="check-square" class="w-4 h-4 shrink-0"></i>
                            <span>Penilaian</span>
                        </a>

                        <!-- Pengaturan -->
                        <a href="{{ route('admin.pengaturan') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ $active === 'pengaturan' ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/70' }}">
                            <i data-lucide="settings" class="w-4 h-4 shrink-0"></i>
                            <span>Pengaturan</span>
                        </a>
                    </nav>
                </div>

                <!-- Admin Profile Card in Sidebar -->
                <div class="mt-8 bg-slate-50 border border-slate-100 rounded-2xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-800 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-inner">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-sm font-bold text-slate-800 truncate uppercase tracking-wide">
                                {{ Auth::user()->name ?? 'ADMIN' }}
                            </div>
                            <div class="text-xs text-slate-400 truncate">
                                Administrator
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="document.getElementById('logout-confirmation-modal').showModal()"
                                class="w-full bg-white hover:bg-red-50 text-red-500 hover:text-red-600 border border-red-100 text-xs font-semibold py-2 px-3 rounded-xl flex items-center justify-center gap-2 transition shadow-xs">
                        <i data-lucide="log-out" class="w-3.5 h-3.5 text-red-500"></i>
                        <span>Keluar Akun</span>
                    </button>
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
                    <div class="relative" id="admin-notification-menu"
                         data-feed-url="{{ route('admin.notifications') }}"
                         data-read-url-template="{{ route('admin.notifications.read', ['notification' => '__notification__']) }}"
                         data-read-all-url="{{ route('admin.notifications.read-all') }}">
                        <button id="admin-notification-toggle" type="button" aria-expanded="false" aria-controls="admin-notification-panel" class="relative rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" title="Notifikasi">
                            <i data-lucide="bell" class="h-5 w-5"></i>
                            @php($unreadNotificationCount = Auth::user()->unreadNotifications()->count())
                            <span id="admin-notification-count" class="absolute -right-0.5 -top-0.5 min-w-4 rounded-full bg-rose-500 px-1 text-center text-[10px] font-bold leading-4 text-white {{ $unreadNotificationCount ? '' : 'hidden' }}">{{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}</span>
                        </button>
                        <section id="admin-notification-panel" class="absolute right-0 top-12 z-30 hidden w-[min(22rem,calc(100vw-2rem))] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
                            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                                <h2 class="text-sm font-bold text-slate-900">Notifikasi</h2>
                                <button id="admin-notification-read-all" type="button" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">Tandai dibaca</button>
                            </div>
                            <div id="admin-notification-error" role="alert" class="hidden px-4 py-3 text-xs text-rose-700"></div>
                            <div id="admin-notification-list" class="max-h-96 divide-y divide-slate-100 overflow-y-auto"></div>
                        </section>
                    </div>

                    <!-- Help Icon -->
                    <a href="{{ route('beranda') }}" target="_blank" class="p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100 transition" title="Lihat Website Pembelajaran">
                        <i data-lucide="help-circle" class="w-5 h-5"></i>
                    </a>

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

    <x-logout-confirmation />

    @stack('scripts')
    <script>
        (() => {
            const menu = document.getElementById('admin-notification-menu');
            if (!menu) return;

            const toggle = document.getElementById('admin-notification-toggle');
            const panel = document.getElementById('admin-notification-panel');
            const list = document.getElementById('admin-notification-list');
            const count = document.getElementById('admin-notification-count');
            const error = document.getElementById('admin-notification-error');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            const showError = (message) => {
                error.textContent = message;
                error.classList.remove('hidden');
            };

            const loadNotifications = async () => {
                try {
                    const response = await fetch(menu.dataset.feedUrl, { headers: { Accept: 'application/json' } });
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    const payload = await response.json();
                    error.classList.add('hidden');
                    count.textContent = payload.unread_count > 99 ? '99+' : payload.unread_count;
                    count.classList.toggle('hidden', payload.unread_count < 1);
                    list.replaceChildren();

                    if (!payload.notifications.length) {
                        const empty = document.createElement('p');
                        empty.className = 'px-4 py-6 text-center text-sm text-slate-500';
                        empty.textContent = 'Belum ada notifikasi.';
                        list.append(empty);
                        return;
                    }

                    payload.notifications.forEach((notification) => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'block w-full px-4 py-3 text-left transition hover:bg-slate-50' + (notification.read_at ? '' : ' bg-emerald-50/60');
                        const title = document.createElement('span');
                        title.className = 'block text-sm font-bold text-slate-800';
                        title.textContent = notification.data.title || 'Notifikasi baru';
                        const message = document.createElement('span');
                        message.className = 'mt-1 block text-xs leading-5 text-slate-600';
                        message.textContent = notification.data.message || '';
                        const time = document.createElement('span');
                        time.className = 'mt-1 block text-[11px] text-slate-400';
                        time.textContent = notification.created_at || '';
                        item.append(title, message, time);
                        item.addEventListener('click', async () => {
                            try {
                                const readUrl = menu.dataset.readUrlTemplate.replace('__notification__', encodeURIComponent(notification.id));
                                const readResponse = await fetch(readUrl, {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' }
                                });
                                if (!readResponse.ok) throw new Error('HTTP ' + readResponse.status);
                                const target = notification.data.url;
                                if (target && new URL(target, window.location.origin).origin === window.location.origin) {
                                    window.location.assign(target);
                                } else {
                                    await loadNotifications();
                                }
                            } catch (requestError) {
                                showError('Notifikasi gagal diperbarui. Coba lagi.');
                            }
                        });
                        list.append(item);
                    });
                } catch (requestError) {
                    showError('Notifikasi gagal dimuat; periksa koneksi server atau database.');
                }
            };

            toggle.addEventListener('click', async () => {
                const isOpen = panel.classList.toggle('hidden') === false;
                toggle.setAttribute('aria-expanded', String(isOpen));
                if (isOpen) await loadNotifications();
            });

            document.getElementById('admin-notification-read-all').addEventListener('click', async () => {
                try {
                    const response = await fetch(menu.dataset.readAllUrl, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' }
                    });
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    await loadNotifications();
                } catch (requestError) {
                    showError('Notifikasi gagal diperbarui. Coba lagi.');
                }
            });

            window.setInterval(loadNotifications, 30000);
        })();
    </script>
    <script>
        if (window.lucide) {
            lucide.createIcons();
        }
    </script>
</body>
</html>
