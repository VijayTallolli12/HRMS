@props([
    'value' => '0',
    'label' => '',
    'icon' => '',
    'trend' => null,
    'trendLabel' => '',
    'color' => 'indigo',
])

@php
$colorMap = [
    'indigo' => 'bg-indigo-50 text-indigo-600 border-indigo-200',
    'green' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
    'blue' => 'bg-sky-50 text-sky-600 border-sky-200',
    'amber' => 'bg-amber-50 text-amber-600 border-amber-200',
    'red' => 'bg-rose-50 text-rose-600 border-rose-200',
    'purple' => 'bg-purple-50 text-purple-600 border-purple-200',
];

$iconBg = $colorMap[$color] ?? $colorMap['indigo'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow duration-200']) }}>
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-500 truncate">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $value }}</p>
            @if($trend !== null)
                <div class="mt-2 flex items-center gap-1.5">
                    @if($trend >= 0)
                        <span class="inline-flex items-center text-xs font-medium text-emerald-600">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" /></svg>
                            {{ abs($trend) }}%
                        </span>
                    @else
                        <span class="inline-flex items-center text-xs font-medium text-rose-600">
                            <svg class="w-3 h-3 rotate-180" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" /></svg>
                            {{ abs($trend) }}%
                        </span>
                    @endif
                    @if($trendLabel)
                        <span class="text-xs text-gray-400">{{ $trendLabel }}</span>
                    @endif
                </div>
            @endif
        </div>
        @if($icon)
            <div class="flex-shrink-0 ml-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-xl {{ $iconBg }} border">
                    <x-heroicon :name="$icon" class="w-6 h-6" />
                </div>
            </div>
        @endif
    </div>
</div>
