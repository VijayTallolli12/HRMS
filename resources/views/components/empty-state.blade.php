@props(['title' => 'No records found', 'description' => 'Get started by creating a new record.', 'icon' => 'document-text'])

<div class="text-center py-12 px-6">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
        <x-heroicon :name="$icon" class="w-8 h-8 text-gray-400" />
    </div>
    <h3 class="text-sm font-semibold text-gray-900">{{ $title }}</h3>
    <p class="mt-1.5 text-sm text-gray-500 max-w-sm mx-auto">{{ $description }}</p>
    @if($slot->isNotEmpty())
        <div class="mt-5">
            {{ $slot }}
        </div>
    @endif
</div>
