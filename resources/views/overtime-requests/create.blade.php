<x-app-layout>
    <x-page-header title="Create Overtime Request" description="Submit a new overtime request for an employee." icon="clock-solid">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('overtime-requests.index') }}" wire:navigate>Overtime Requests</a>
            <span class="breadcrumb-separator">/</span>
            <span>Create</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('overtime-requests.store') }}" method="POST">
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
                        <label for="employee_id" class="label">Employee <span class="text-red-500">*</span></label>
                        <select id="employee_id" name="employee_id" class="select-field" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date" class="label">Date <span class="text-red-500">*</span></label>
                            <input id="date" name="date" type="date" class="input-field" value="{{ old('date') }}" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>
                        <div>
                            <label for="hours" class="label">Hours <span class="text-red-500">*</span></label>
                            <input id="hours" name="hours" type="number" step="0.5" min="0.5" max="24" class="input-field" value="{{ old('hours') }}" required />
                            <x-input-error :messages="$errors->get('hours')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label for="reason" class="label">Reason <span class="text-red-500">*</span></label>
                        <textarea id="reason" name="reason" rows="3" class="textarea-field" required>{{ old('reason') }}</textarea>
                        <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('overtime-requests.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Create Request</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
