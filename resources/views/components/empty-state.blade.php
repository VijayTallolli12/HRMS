@props(['title' => 'No records found', 'description' => 'Get started by creating a new record.', 'icon' => 'document-text'])

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <div class="empty-state-icon">
        <x-heroicon :name="$icon" class="w-7 h-7 text-gray-400" />
    </div>
    <h3 class="empty-state-title">{{ $title }}</h3>
    <p class="empty-state-description">{{ $description }}</p>
    @if($slot->isNotEmpty())
        <div class="mt-5 flex flex-wrap items-center justify-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>

