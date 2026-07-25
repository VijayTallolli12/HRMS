@props(['title' => '', 'icon' => ''])

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            @if($icon)
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600">
                    <x-heroicon :name="$icon" class="w-5 h-5" />
                </div>
            @endif
            <div>
                @if(isset($breadcrumb) && $breadcrumb)
                    <nav class="flex items-center gap-1.5 text-xs text-gray-400 mb-1">
                        {{ $breadcrumb }}
                    </nav>
                @endif
                <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
            </div>
        </div>
        <div class="flex items-center gap-3">
            {{ $slot }}
        </div>
    </div>
</div>
