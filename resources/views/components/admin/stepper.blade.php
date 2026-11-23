{{-- Pemakaian: <x-admin.stepper :current="1" /> (1 Detail Soal, 2 Test Case, 3 Terbitkan) --}}
@props(['current' => 1])
@php $steps = ['Detail Soal', 'Test Case', 'Terbitkan']; @endphp
<ol class="flex items-center gap-2 mb-6 text-xs font-semibold" aria-label="Langkah membuat soal">
    @foreach ($steps as $i => $label)
        @php $n = $i + 1; @endphp
        <li class="flex items-center gap-2 {{ $n <= $current ? 'text-emerald-700' : 'text-slate-400' }}"
            @if ($n === $current) aria-current="step" @endif>
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-[11px] {{ $n < $current ? 'bg-emerald-600 text-white' : ($n === $current ? 'bg-emerald-100 text-emerald-800 ring-2 ring-emerald-500' : 'bg-slate-100') }}">
                @if ($n < $current)
                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                @else
                    {{ $n }}
                @endif
            </span>
            {{ $label }}
        </li>
        @unless ($loop->last)<li aria-hidden="true" class="w-6 h-px bg-slate-200"></li>@endunless
    @endforeach
</ol>