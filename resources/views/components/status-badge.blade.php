@props(['status'])

@php
    $map = [
        'new' => 'badge badge-new',
        'contacted' => 'badge badge-contacted',
        'qualified' => 'badge badge-qualified',
        'lost' => 'badge badge-lost',
    ];
@endphp

<span class="{{ $map[$status] ?? 'badge' }}">{{ ucfirst($status) }}</span>
