<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit {{ $shift->name }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('shifts.update', $shift) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="organization_id" value="Organization" />
                            <select id="organization_id" name="organization_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Organization</option>
                                @foreach ($organizations as $organization)
                                    <option value="{{ $organization->id }}" {{ old('organization_id', $shift->organization_id) == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="name" value="Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $shift->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="start_time" value="Start Time" />
                            <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full" :value="old('start_time', $shift->start_time)" required />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="end_time" value="End Time" />
                            <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full" :value="old('end_time', $shift->end_time)" required />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="break_minutes" value="Break (minutes)" />
                            <x-text-input id="break_minutes" name="break_minutes" type="number" class="mt-1 block w-full" :value="old('break_minutes', $shift->break_minutes)" />
                            <x-input-error :messages="$errors->get('break_minutes')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $shift->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="is_active" value="Status" />
                            <select id="is_active" name="is_active" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="1" {{ old('is_active', $shift->is_active) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !old('is_active', $shift->is_active) ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('shifts.index') }}" class="text-gray-600 hover:text-gray-900" wire:navigate>Cancel</a>
                            <x-primary-button type="submit">Update Shift</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
