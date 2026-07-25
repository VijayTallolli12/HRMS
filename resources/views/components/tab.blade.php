@props(['name' => '', 'label' => '', 'icon' => ''])

<button
    type="button"
    x-on:click="activeTab = '{{ $name }}'"
    x-bind:class="activeTab === '{{ $name }}'
        ? 'border-indigo-500 text-indigo-600'
        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
    class="flex items-center gap-2 whitespace-nowrap border-b-2 px-4 py-3 text-sm font-medium transition-colors duration-150"
    role="tab"
    x-bind:aria-selected="activeTab === '{{ $name }}'"
>
    @if($icon)
        <x-heroicon :name="$icon" class="w-4 h-4" />
    @endif
    {{ $label }}
</button>
