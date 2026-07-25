<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Late Policy</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('late-policies.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="organization_id" value="Organization" />
                            <select id="organization_id" name="organization_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Organization</option>
                                @foreach ($organizations as $organization)
                                    <option value="{{ $organization->id }}" {{ old('organization_id') == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="name" value="Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="grace_minutes" value="Grace Minutes" />
                            <x-text-input id="grace_minutes" name="grace_minutes" type="number" min="0" max="60" class="mt-1 block w-full" :value="old('grace_minutes', 0)" required />
                            <x-input-error :messages="$errors->get('grace_minutes')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="max_late_per_month" value="Max Late Per Month" />
                            <x-text-input id="max_late_per_month" name="max_late_per_month" type="number" min="1" max="31" class="mt-1 block w-full" :value="old('max_late_per_month', 3)" required />
                            <x-input-error :messages="$errors->get('max_late_per_month')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="penalty_type" value="Penalty Type" />
                            <select id="penalty_type" name="penalty_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required x-data="{ penaltyType: '{{ old('penalty_type', 'warning') }}' }" x-model="penaltyType">
                                <option value="warning" {{ old('penalty_type') == 'warning' ? 'selected' : '' }}>Warning</option>
                                <option value="deduction" {{ old('penalty_type') == 'deduction' ? 'selected' : '' }}>Deduction</option>
                                <option value="suspension" {{ old('penalty_type') == 'suspension' ? 'selected' : '' }}>Suspension</option>
                            </select>
                            <x-input-error :messages="$errors->get('penalty_type')" class="mt-2" />
                        </div>
                        <div x-data="{ penaltyType: '{{ old('penalty_type', 'warning') }}' }" x-show="penaltyType === 'deduction'" x-cloak>
                            <x-input-label for="penalty_amount" value="Penalty Amount" />
                            <x-text-input id="penalty_amount" name="penalty_amount" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('penalty_amount', 0)" />
                            <x-input-error :messages="$errors->get('penalty_amount')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('late-policies.index') }}" class="text-gray-600 hover:text-gray-900" wire:navigate>Cancel</a>
                            <x-primary-button type="submit">Create Late Policy</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
