@props(['status' => 'active'])

@php
    $colors = match($status) {
        'active', 'present', 'approved', 'paid', 'completed' => 'badge-success',
        'inactive', 'absent', 'rejected', 'terminated', 'cancelled' => 'badge-danger',
        'pending', 'processing', 'draft', 'on-hold', 'suspended' => 'badge-warning',
        'late', 'half-day', 'remote', 'generated' => 'badge-info',
        default => 'badge-neutral',
    };
@endphp

<span class="{{ $colors }}">
    {{ ucfirst(str_replace('-', ' ', $status)) }}
</span>
