{{-- Pemakaian: <x-admin.breadcrumb :items="[['Latihan & Soal', route('admin.latihan')], ['Tambah Latihan', null]]" /> --}}
@props(['items' => []])
<nav class="flex flex-wrap items-center gap-1.5 text-xs text-slate-400 mb-5" aria-label="Breadcrumb">
    @foreach ($items as [$label, $url])
        @if (! $loop->first)
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        @endif
        @if ($url && ! $loop->last)
            <a href="{{ $url }}" class="hover:text-emerald-600 font-semibold">{{ $label }}</a>
        @else
            <span class="text-slate-700 font-semibold" @if ($loop->last) aria-current="page" @endif>{{ $label }}</span>
        @endif
    @endforeach
</nav>