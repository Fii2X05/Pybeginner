@php
    // Kelas ditulis lengkap agar terdeteksi Tailwind (tidak boleh dirakit dari variabel).
    $styles = [
        'status'  => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'error'   => 'border-rose-200 bg-rose-50 text-rose-800',
    ];
@endphp
@foreach ($styles as $key => $class)
    @if (session($key))
        <div role="status" class="mb-4 rounded-xl border text-sm font-medium px-4 py-3 {{ $class }}">{{ session($key) }}</div>
    @endif
@endforeach
@if ($errors->any())
    <div role="alert" class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
        @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
    </div>
@endif