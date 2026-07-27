<x-app-layout>
    <x-page-header title="Create Late Policy" description="Define rules for handling late attendance." icon="exclamation-triangle">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('late-policies.index') }}" wire:navigate>Late Policies</a>
            <span class="breadcrumb-separator">/</span>
            <span>Create</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('late-policies.store') }}" method="POST">
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
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="grace_minutes" class="label">Grace Minutes <span class="text-red-500">*</span></label>
                            <input id="grace_minutes" name="grace_minutes" type="number" min="0" max="60" class="input-field" value="{{ old('grace_minutes', 0) }}" required />
                            <x-input-error :messages="$errors->get('grace_minutes')" class="mt-2" />
                        </div>
                        <div>
                            <label for="max_late_per_month" class="label">Max Late/Month <span class="text-red-500">*</span></label>
                            <input id="max_late_per_month" name="max_late_per_month" type="number" min="1" max="31" class="input-field" value="{{ old('max_late_per_month', 3) }}" required />
                            <x-input-error :messages="$errors->get('max_late_per_month')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label for="penalty_type" class="label">Penalty Type <span class="text-red-500">*</span></label>
                        <select id="penalty_type" name="penalty_type" class="select-field" required x-data="{ penaltyType: '{{ old('penalty_type', 'warning') }}' }" x-model="penaltyType">
                            <option value="warning" {{ old('penalty_type') == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="deduction" {{ old('penalty_type') == 'deduction' ? 'selected' : '' }}>Deduction</option>
                            <option value="suspension" {{ old('penalty_type') == 'suspension' ? 'selected' : '' }}>Suspension</option>
                        </select>
                        <x-input-error :messages="$errors->get('penalty_type')" class="mt-2" />
                    </div>
                    <div x-data="{ penaltyType: '{{ old('penalty_type', 'warning') }}' }" x-show="penaltyType === 'deduction'" x-cloak>
                        <label for="penalty_amount" class="label">Penalty Amount</label>
                        <input id="penalty_amount" name="penalty_amount" type="number" step="0.01" min="0" class="input-field" value="{{ old('penalty_amount', 0) }}" />
                        <x-input-error :messages="$errors->get('penalty_amount')" class="mt-2" />
                    </div>
                    <div>
                        <label for="description" class="label">Description</label>
                        <textarea id="description" name="description" rows="3" class="textarea-field">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('late-policies.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Create Policy</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
