<x-app-layout>
    <x-page-header title="Create Weekend Policy" description="Define weekend and non-working day rules." icon="calendar-days">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('weekend-policies.index') }}" wire:navigate>Weekend Policies</a>
            <span class="breadcrumb-separator">/</span>
            <span>Create</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('weekend-policies.store') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="organization_id" class="label">Organization <span class="text-red-500">*</span></label>
                        <select id="organization_id" name="organization_id" class="select-field" required>
                            <option value="">Select Organization</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ old('organization_id') == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                    </div>
                    <div>
                        <label for="name" class="label">Name <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" class="input-field" value="{{ old('name') }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <label class="label">Weekend Days <span class="text-red-500">*</span></label>
                        <div class="mt-2 flex flex-wrap gap-4">
                            @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="weekend_days[]" value="{{ $day }}" {{ in_array($day, old('weekend_days', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                    <span class="text-body text-gray-700">{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('weekend_days')" class="mt-2" />
                    </div>
                    <div>
                        <label for="description" class="label">Description</label>
                        <textarea id="description" name="description" rows="3" class="textarea-field">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('weekend-policies.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Create Policy</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
