@props(['status'])

@php
    $styles = [
        'menunggu' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200',
        'disetujui' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200',
        'ditolak' => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-200',
    ];
    $dots = [
        'menunggu' => 'bg-amber-500',
        'disetujui' => 'bg-emerald-500',
        'ditolak' => 'bg-rose-500',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full shrink-0 ' . ($styles[$status] ?? '')]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $dots[$status] ?? 'bg-slate-400' }}"></span>
    {{ ucfirst($status) }}
</span>
