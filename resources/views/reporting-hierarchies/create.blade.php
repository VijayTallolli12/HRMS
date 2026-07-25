<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Reporting Hierarchy</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('reporting-hierarchies.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="employee_id" value="Employee" />
                            <select id="employee_id" name="employee_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="manager_id" value="Manager" />
                            <select id="manager_id" name="manager_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Manager</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('manager_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('manager_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="reporting_type" value="Reporting Type" />
                            <select id="reporting_type" name="reporting_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Type</option>
                                <option value="Direct" {{ old('reporting_type') == 'Direct' ? 'selected' : '' }}>Direct</option>
                                <option value="Functional" {{ old('reporting_type') == 'Functional' ? 'selected' : '' }}>Functional</option>
                                <option value="Administrative" {{ old('reporting_type') == 'Administrative' ? 'selected' : '' }}>Administrative</option>
                            </select>
                            <x-input-error :messages="$errors->get('reporting_type')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="effective_from" value="Effective From" />
                            <x-text-input id="effective_from" name="effective_from" type="date" class="mt-1 block w-full" :value="old('effective_from')" required />
                            <x-input-error :messages="$errors->get('effective_from')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="effective_to" value="Effective To" />
                            <x-text-input id="effective_to" name="effective_to" type="date" class="mt-1 block w-full" :value="old('effective_to')" />
                            <x-input-error :messages="$errors->get('effective_to')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('reporting-hierarchies.index') }}" class="text-gray-600 hover:text-gray-900" wire:navigate>Cancel</a>
                            <x-primary-button type="submit">Create Reporting Hierarchy</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
