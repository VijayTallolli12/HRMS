<x-app-layout>
    <x-page-header title="Create Leave Type" icon="heroicon-o-calendar">
        <x-secondary-button tag="a" href="{{ route('leave-types.index') }}" wire:navigate>
            <x-heroicon name="heroicon-o-arrow-left" class="h-4 w-4" />
            Back
        </x-secondary-button>
    </x-page-header>

    <div class="card p-6">
        <form action="{{ route('leave-types.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="days_per_year" value="Days Per Year" />
                    <x-text-input id="days_per_year" name="days_per_year" type="number" class="mt-1 block w-full" :value="old('days_per_year')" required />
                    @error('days_per_year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                        <span class="label">Is Active</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                <x-secondary-button tag="a" href="{{ route('leave-types.index') }}" wire:navigate>Cancel</x-secondary-button>
                <x-primary-button type="submit">Create Leave Type</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
