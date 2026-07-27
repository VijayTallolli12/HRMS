<x-app-layout>
    <x-page-header title="Edit Leave Type" description="Update leave type details." icon="tag">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('leave-types.index') }}" wire:navigate>Leave Types</a>
            <span class="breadcrumb-separator">/</span>
            <span>Edit</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('leave-types.update', $leaveType) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="label">Name <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" class="input-field" value="{{ old('name', $leaveType->name) }}" required autofocus />
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="days_per_year" class="label">Days Per Year <span class="text-red-500">*</span></label>
                        <input id="days_per_year" name="days_per_year" type="number" class="input-field" value="{{ old('days_per_year', $leaveType->days_per_year) }}" required />
                        @error('days_per_year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $leaveType->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500" />
                            <span class="label mb-0">Is Active</span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                    <a href="{{ route('leave-types.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                    <x-primary-button type="submit">Update Leave Type</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
