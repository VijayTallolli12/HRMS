<x-app-layout>
    <x-page-header title="Request Leave" description="Submit a new leave request for an employee." icon="calendar">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('leaves.index') }}" wire:navigate>Leave Management</a>
            <span class="breadcrumb-separator">/</span>
            <span>Request Leave</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('leaves.store') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="organization_id" class="label">Organization <span class="text-red-500">*</span></label>
                        <select id="organization_id" name="organization_id" class="select-field" required>
                            <option value="">Select Organization</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ old('organization_id', $selectedOrgId ?? '') == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                    </div>
                    <div>
                        <label for="employee_id" class="label">Employee <span class="text-red-500">*</span></label>
                        <select id="employee_id" name="employee_id" class="select-field" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                    </div>
                    <div>
                        <label for="leave_type" class="label">Leave Type <span class="text-red-500">*</span></label>
                        <select id="leave_type" name="leave_type" class="select-field" required>
                            <option value="">Select Leave Type</option>
                            <option value="annual" {{ old('leave_type') == 'annual' ? 'selected' : '' }}>Annual</option>
                            <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>Sick</option>
                            <option value="personal" {{ old('leave_type') == 'personal' ? 'selected' : '' }}>Personal</option>
                            <option value="unpaid" {{ old('leave_type') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="maternity" {{ old('leave_type') == 'maternity' ? 'selected' : '' }}>Maternity</option>
                            <option value="paternity" {{ old('leave_type') == 'paternity' ? 'selected' : '' }}>Paternity</option>
                        </select>
                        <x-input-error :messages="$errors->get('leave_type')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="label">Start Date <span class="text-red-500">*</span></label>
                            <input id="start_date" name="start_date" type="date" class="input-field" value="{{ old('start_date') }}" required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>
                        <div>
                            <label for="end_date" class="label">End Date <span class="text-red-500">*</span></label>
                            <input id="end_date" name="end_date" type="date" class="input-field" value="{{ old('end_date') }}" required />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label for="days" class="label">Days <span class="text-red-500">*</span></label>
                        <input id="days" name="days" type="number" class="input-field" value="{{ old('days') }}" min="1" required />
                        <x-input-error :messages="$errors->get('days')" class="mt-2" />
                    </div>
                    <div>
                        <label for="reason" class="label">Reason</label>
                        <textarea id="reason" name="reason" rows="3" class="textarea-field">{{ old('reason') }}</textarea>
                        <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('leaves.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Create Leave</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
