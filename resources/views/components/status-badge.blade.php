@props(['status'])

@php
    $map = [
        'new' => 'inline-flex rounded-full bg-blue-100 text-blue-700 px-2.5 py-1 text-xs font-semibold',
        'contacted' => 'inline-flex rounded-full bg-amber-100 text-amber-700 px-2.5 py-1 text-xs font-semibold',
        'qualified' => 'inline-flex rounded-full bg-emerald-100 text-emerald-700 px-2.5 py-1 text-xs font-semibold',
        'lost' => 'inline-flex rounded-full bg-red-100 text-red-700 px-2.5 py-1 text-xs font-semibold',
    ];
@endphp

<span class="{{ $map[$status] ?? 'inline-flex rounded-full bg-slate-100 text-slate-700 px-2.5 py-1 text-xs font-semibold' }}">{{ ucfirst($status) }}</span>
