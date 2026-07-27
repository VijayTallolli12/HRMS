<x-app-layout>
    <x-page-header title="Create Work Schedule" description="Define working hours and days for a new schedule." icon="clock">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('work-schedules.index') }}" wire:navigate>Work Schedules</a>
            <span class="breadcrumb-separator">/</span>
            <span>Create</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('work-schedules.store') }}" method="POST">
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
                        <label class="label">Working Days <span class="text-red-500">*</span></label>
                        <div class="mt-2 flex flex-wrap gap-4">
                            @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="working_days[]" value="{{ $day }}" {{ in_array($day, old('working_days', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                    <span class="text-body text-gray-700">{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('working_days')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="hours_per_day" class="label">Hours per Day <span class="text-red-500">*</span></label>
                            <input id="hours_per_day" name="hours_per_day" type="number" step="0.25" class="input-field" value="{{ old('hours_per_day', 8) }}" required />
                            <x-input-error :messages="$errors->get('hours_per_day')" class="mt-2" />
                        </div>
                        <div>
                            <label for="break_minutes" class="label">Break (minutes)</label>
                            <input id="break_minutes" name="break_minutes" type="number" class="input-field" value="{{ old('break_minutes', 60) }}" />
                            <x-input-error :messages="$errors->get('break_minutes')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label for="description" class="label">Description</label>
                        <textarea id="description" name="description" rows="3" class="textarea-field">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('work-schedules.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Create Schedule</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
