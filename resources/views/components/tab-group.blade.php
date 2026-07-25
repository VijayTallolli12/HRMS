@props(['defaultTab' => ''])

<div x-data="{ activeTab: '{{ $defaultTab }}' }" {{ $attributes }}>
    {{ $slot }}
</div>
