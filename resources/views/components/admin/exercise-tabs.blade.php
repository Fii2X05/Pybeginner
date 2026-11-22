{{-- Pemakaian: <x-admin.exercise-tabs :exercise="$exercise" active="testcase" /> (active: detail | testcase | kunci) --}}
@props(['exercise', 'active'])
@php
    $tabs = [
        'detail'   => ['Detail Soal',       route('admin.latihan.edit', $exercise),       'file-text'],
        'testcase' => ['Test Case',         route('admin.latihan.test-cases', $exercise), 'list-checks'],
        'kunci'    => ['Uji Kunci Jawaban', route('admin.latihan.answer-key', $exercise), 'flask-conical'],
    ];
@endphp
<div class="flex gap-1 border-b border-slate-200 mb-6 overflow-x-auto" style="overflow-y: hidden;" role="navigation" aria-label="Bagian soal">
    @foreach ($tabs as $key => [$label, $url, $icon])
        <a href="{{ $url }}"
           @if ($active === $key) aria-current="page" @endif
          class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold whitespace-nowrap border-b-2 transition {{ $active === $key ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i> {{ $label }}
        </a>
    @endforeach
</div>