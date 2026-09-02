@props(['status' => 'active'])

@php
    $normalized = strtolower(trim($status ?? 'active'));
    $config = match($normalized) {
        'active', 'present', 'approved', 'paid', 'completed', 'resolved' => [
            'class' => 'badge-success',
            'dot' => 'bg-emerald-500',
        ],
        'inactive', 'absent', 'rejected', 'terminated', 'cancelled', 'dismissed', 'failed' => [
            'class' => 'badge-danger',
            'dot' => 'bg-rose-500',
        ],
        'pending', 'processing', 'draft', 'on-hold', 'suspended', 'preview' => [
            'class' => 'badge-warning',
            'dot' => 'bg-amber-500',
        ],
        'late', 'half-day', 'remote', 'generated', 'regularized' => [
            'class' => 'badge-info',
            'dot' => 'bg-sky-500',
        ],
        'super-admin', 'branch-admin', 'manager', 'hr' => [
            'class' => 'badge-purple',
            'dot' => 'bg-purple-500',
        ],
        default => [
            'class' => 'badge-neutral',
            'dot' => 'bg-gray-400',
        ],
    };
@endphp

<span {{ $attributes->merge(['class' => $config['class']]) }}>
    <span class="badge-dot {{ $config['dot'] }}"></span>
    <span>{{ ucfirst(str_replace(['_', '-'], ' ', $normalized)) }}</span>
</span>

