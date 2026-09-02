@props(['title' => '', 'subtitle' => '', 'height' => '280px'])

<div {{ $attributes->merge(['class' => 'card p-5 sm:p-6']) }}>
    @if($title || $subtitle || isset($actions))
        <div class="flex items-center justify-between gap-4 mb-4">
            <div>
                @if($title)
                    <h3 class="text-section font-semibold text-gray-900">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-caption text-gray-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($actions))
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    <div class="relative w-full" style="height: {{ $height }}">
        {{ $slot }}
    </div>
</div>

