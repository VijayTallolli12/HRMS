@props(['title' => '', 'icon' => '', 'description' => '', 'breadcrumb' => null])

<div class="page-header">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            @if($breadcrumb || isset($breadcrumb) && $breadcrumb !== null)
                <nav class="breadcrumb">
                    {{ $breadcrumb }}
                </nav>
            @endif
            <div class="flex items-center gap-3">
                @if($icon)
                    <div class="flex items-center justify-center w-10 h-10 rounded-card bg-primary-50 text-primary-600 border border-primary-100">
                        <x-heroicon :name="$icon" class="w-5 h-5" />
                    </div>
                @endif
                <div>
                    <h1 class="page-title">{{ $title }}</h1>
                    @if($description)
                        <p class="page-description">{{ $description }}</p>
                    @endif
                </div>
            </div>
        </div>
        @if(isset($actions) && $actions->isNotEmpty())
            <div class="page-actions">
                {{ $actions }}
            </div>
        @elseif($slot->isNotEmpty())
            <div class="page-actions">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>
