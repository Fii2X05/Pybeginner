{{-- navbar: 'learner' (halaman belajar) atau 'landing' (beranda) --}}
{{-- flush : true = konten full-width tanpa container (dipakai landing) --}}
@props(['title' => 'PyBeginner', 'active' => '', 'navbar' => 'learner', 'flush' => false])
<!DOCTYPE html>
<html lang="id" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - PyBeginner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Ikon Lucide, dipakai di landing page --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('styles')
</head>
<body class="flex min-h-screen flex-col bg-emerald-50 text-slate-800 antialiased overflow-x-hidden w-full">

    @if ($navbar === 'landing')
        <x-navbar-landing :active="$active" />
    @else
        <x-navbar-learner :active="$active" />
    @endif

    @if ($flush)
        <main class="flex-1 w-full min-w-0">{{ $slot }}</main>
    @else
        <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 sm:py-8 lg:px-10 lg:py-10 min-w-0">{{ $slot }}</main>
    @endif

    <x-footer />
    <x-logout-confirmation />

    @stack('scripts')
    <script>
        if (window.lucide) lucide.createIcons();
    </script>
</body>
</html>