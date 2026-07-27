@props(['title' => 'No records found', 'description' => 'Get started by creating a new record.', 'icon' => 'document-text'])

<div class="empty-state">
    <div class="empty-state-icon">
        <x-heroicon :name="$icon" class="w-8 h-8 text-gray-300" />
    </div>
    <h3 class="empty-state-title">{{ $title }}</h3>
    <p class="empty-state-description">{{ $description }}</p>
    @if($slot->isNotEmpty())
        <div class="mt-6">
            {{ $slot }}
        </div>
    @endif
</div>
