@props(['status' => 'available'])

@php
    $styles = [
        'available'   => 'bg-[#DCEBD9] text-[#2F5233]',
        'borrowed'    => 'bg-stone-200 text-stone-600',
        'new_arrival' => 'bg-[#152B1E] text-[#E8DCC4]',
        'overdue'     => 'bg-red-100 text-red-700',
        'reserved'    => 'bg-amber-100 text-amber-800',
        'active'      => 'bg-[#DCEBD9] text-[#2F5233]',
        'pending'     => 'bg-amber-100 text-amber-800',
        'returned'    => 'bg-stone-200 text-stone-600',
    ];

    $labels = [
        'available'   => 'Available',
        'borrowed'    => 'Borrowed',
        'new_arrival' => 'New Arrival',
        'overdue'     => 'Overdue',
        'reserved'    => 'Reserved',
        'active'      => 'Active',
        'pending'     => 'Pending Approval',
        'returned'    => 'Returned',
    ];

    $style = $styles[$status] ?? $styles['available'];
    $label = $labels[$status] ?? ucfirst($status);
@endphp

<span {{ $attributes->merge(['class' => "inline-block text-xs font-medium px-3 py-1 rounded-full $style"]) }}>
    {{ $slot->isEmpty() ? $label : $slot }}
</span>