@props(['status' => 'active'])

@php
    $colors = match($status) {
        'active', 'present', 'approved', 'paid', 'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'inactive', 'absent', 'rejected', 'terminated', 'cancelled' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        'pending', 'processing', 'draft', 'on-hold', 'suspended' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'late', 'half-day', 'remote', 'generated' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
        default => 'bg-gray-50 text-gray-700 ring-gray-600/20',
    };
@endphp

<span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset {{ $colors }}">
    {{ ucfirst(str_replace('-', ' ', $status)) }}
</span>
