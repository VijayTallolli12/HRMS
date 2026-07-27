@props(['title' => '', 'height' => '300px'])

<div {{ $attributes->merge(['class' => 'card p-6']) }}>
    @if($title)
        <h3 class="text-section text-gray-900 mb-5">{{ $title }}</h3>
    @endif
    <div style="height: {{ $height }}">
        {{ $slot }}
    </div>
</div>
