<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit {{ $workSchedule->name }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('work-schedules.update', $workSchedule) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="organization_id" value="Organization" />
                            <select id="organization_id" name="organization_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Organization</option>
                                @foreach ($organizations as $organization)
                                    <option value="{{ $organization->id }}" {{ old('organization_id', $workSchedule->organization_id) == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="name" value="Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $workSchedule->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label value="Working Days" />
                            <div class="mt-2 flex flex-wrap gap-4">
                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                    <label class="inline-flex items-center gap-2">
                                        <input type="checkbox" name="working_days[]" value="{{ $day }}" {{ in_array($day, old('working_days', $workSchedule->working_days ?? [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                        <span class="text-sm text-gray-700">{{ $day }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('working_days')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="hours_per_day" value="Hours per Day" />
                            <x-text-input id="hours_per_day" name="hours_per_day" type="number" step="0.25" class="mt-1 block w-full" :value="old('hours_per_day', $workSchedule->hours_per_day)" required />
                            <x-input-error :messages="$errors->get('hours_per_day')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="break_minutes" value="Break (minutes)" />
                            <x-text-input id="break_minutes" name="break_minutes" type="number" class="mt-1 block w-full" :value="old('break_minutes', $workSchedule->break_minutes)" />
                            <x-input-error :messages="$errors->get('break_minutes')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $workSchedule->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="is_active" value="Status" />
                            <select id="is_active" name="is_active" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="1" {{ old('is_active', $workSchedule->is_active) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !old('is_active', $workSchedule->is_active) ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('work-schedules.index') }}" class="text-gray-600 hover:text-gray-900" wire:navigate>Cancel</a>
                            <x-primary-button type="submit">Update Work Schedule</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
