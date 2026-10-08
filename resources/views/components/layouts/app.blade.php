{{-- navbar: 'learner' (halaman belajar) atau 'landing' (beranda) --}}
{{-- flush : true = konten full-width tanpa container (dipakai landing) --}}
@props(['title' => 'PyBeginner', 'active' => '', 'navbar' => 'learner', 'flush' => false])
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - PyBeginner</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Ikon Lucide, dipakai di landing page --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    @stack('styles')
</head>
<body class="flex min-h-screen flex-col bg-emerald-50 text-slate-800 antialiased">

    @if ($navbar === 'landing')
        <x-navbar-landing :active="$active" />
    @else
        <x-navbar-learner :active="$active" />
    @endif

    @if ($flush)
        <main class="flex-1">{{ $slot }}</main>
    @else
        <main class="mx-auto w-full max-w-7xl flex-1 px-6 py-10 lg:px-10">{{ $slot }}</main>
    @endif

    <x-footer />

    @stack('scripts')
    <script>
        if (window.lucide) lucide.createIcons();
    </script>
</body>
</html>