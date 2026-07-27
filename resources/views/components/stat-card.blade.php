@props([
    'value' => '0',
    'label' => '',
    'icon' => '',
    'trend' => null,
    'trendLabel' => '',
    'color' => 'indigo',
])

@php
$iconBg = match($color) {
    'indigo' => 'bg-primary-50 text-primary-600',
    'green' => 'bg-emerald-50 text-emerald-600',
    'blue' => 'bg-sky-50 text-sky-600',
    'amber' => 'bg-amber-50 text-amber-600',
    'red' => 'bg-red-50 text-red-600',
    'purple' => 'bg-purple-50 text-purple-600',
    default => 'bg-gray-50 text-gray-600',
};
@endphp

<div {{ $attributes->merge(['class' => 'card p-5 hover:shadow-card-hover transition-all duration-200 group']) }}>
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <p class="text-micro font-medium text-gray-500 uppercase tracking-wide">{{ $label }}</p>
            <p class="mt-2 text-[28px] font-bold text-gray-900 tracking-tight leading-none">{{ $value }}</p>
            @if($trend !== null)
                <div class="mt-2.5 flex items-center gap-1">
                    @if($trend >= 0)
                        <span class="inline-flex items-center text-micro font-medium text-emerald-600">
                            <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" /></svg>
                            {{ abs($trend) }}%
                        </span>
                    @else
                        <span class="inline-flex items-center text-micro font-medium text-red-600">
                            <svg class="w-3 h-3 mr-0.5 rotate-180" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" /></svg>
                            {{ abs($trend) }}%
                        </span>
                    @endif
                    @if($trendLabel)
                        <span class="text-micro text-gray-400 ml-0.5">{{ $trendLabel }}</span>
                    @endif
                </div>
            @endif
        </div>
        @if($icon)
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center w-10 h-10 rounded-card {{ $iconBg }} transition-transform duration-200 group-hover:scale-105">
                    <x-heroicon :name="$icon" class="w-5 h-5" />
                </div>
            </div>
        @endif
    </div>
</div>
