@props(['title' => '', 'height' => '300px'])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-gray-200 shadow-sm p-6']) }}>
    @if($title)
        <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ $title }}</h3>
    @endif
    <div style="height: {{ $height }}">
        {{ $slot }}
    </div>
</div>
