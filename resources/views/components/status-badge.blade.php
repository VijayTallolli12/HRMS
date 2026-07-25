@props(['status' => 'active'])

@php
    $colors = match($status) {
        'active' => 'bg-green-100 text-green-800',
        'inactive' => 'bg-gray-100 text-gray-800',
        'terminated' => 'bg-red-100 text-red-800',
        'suspended' => 'bg-yellow-100 text-yellow-800',
        default => 'bg-gray-100 text-gray-800',
    };
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colors }}">
    {{ ucfirst($status) }}
</span>
