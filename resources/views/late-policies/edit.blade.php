<x-app-layout>
    <x-page-header title="Edit Late Policy" description="Update late attendance policy details." icon="exclamation-triangle">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('late-policies.index') }}" wire:navigate>Late Policies</a>
            <span class="breadcrumb-separator">/</span>
            <span>Edit</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('late-policies.update', $latePolicy) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label for="organization_id" class="label">Organization <span class="text-red-500">*</span></label>
                        <select id="organization_id" name="organization_id" class="select-field" required>
                            <option value="">Select Organization</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ old('organization_id', $latePolicy->organization_id) == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                    </div>
                    <div>
                        <label for="name" class="label">Name <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" class="input-field" value="{{ old('name', $latePolicy->name) }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="grace_minutes" class="label">Grace Minutes <span class="text-red-500">*</span></label>
                            <input id="grace_minutes" name="grace_minutes" type="number" min="0" max="60" class="input-field" value="{{ old('grace_minutes', $latePolicy->grace_minutes) }}" required />
                            <x-input-error :messages="$errors->get('grace_minutes')" class="mt-2" />
                        </div>
                        <div>
                            <label for="max_late_per_month" class="label">Max Late/Month <span class="text-red-500">*</span></label>
                            <input id="max_late_per_month" name="max_late_per_month" type="number" min="1" max="31" class="input-field" value="{{ old('max_late_per_month', $latePolicy->max_late_per_month) }}" required />
                            <x-input-error :messages="$errors->get('max_late_per_month')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label for="penalty_type" class="label">Penalty Type <span class="text-red-500">*</span></label>
                        <select id="penalty_type" name="penalty_type" class="select-field" required x-data="{ penaltyType: '{{ old('penalty_type', $latePolicy->penalty_type) }}' }" x-model="penaltyType">
                            <option value="warning" {{ old('penalty_type', $latePolicy->penalty_type) == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="deduction" {{ old('penalty_type', $latePolicy->penalty_type) == 'deduction' ? 'selected' : '' }}>Deduction</option>
                            <option value="suspension" {{ old('penalty_type', $latePolicy->penalty_type) == 'suspension' ? 'selected' : '' }}>Suspension</option>
                        </select>
                        <x-input-error :messages="$errors->get('penalty_type')" class="mt-2" />
                    </div>
                    <div x-data="{ penaltyType: '{{ old('penalty_type', $latePolicy->penalty_type) }}' }" x-show="penaltyType === 'deduction'" x-cloak>
                        <label for="penalty_amount" class="label">Penalty Amount</label>
                        <input id="penalty_amount" name="penalty_amount" type="number" step="0.01" min="0" class="input-field" value="{{ old('penalty_amount', $latePolicy->penalty_amount) }}" />
                        <x-input-error :messages="$errors->get('penalty_amount')" class="mt-2" />
                    </div>
                    <div>
                        <label for="description" class="label">Description</label>
                        <textarea id="description" name="description" rows="3" class="textarea-field">{{ old('description', $latePolicy->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div>
                        <label for="is_active" class="label">Status</label>
                        <select id="is_active" name="is_active" class="select-field" required>
                            <option value="1" {{ old('is_active', $latePolicy->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $latePolicy->is_active) ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('late-policies.show', $latePolicy) }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Update Policy</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
