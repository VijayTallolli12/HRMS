<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Edit {{ $department->name }}"
            icon="pencil-square"
            description="Update department information."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('departments.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Departments</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Edit {{ $department->name }}</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card">
            <form action="{{ route('departments.update', $department) }}" method="POST" x-data="{ branches: @js($branches->map(fn ($branch) => ['id' => $branch->id, 'name' => $branch->name, 'organization_id' => $branch->organization_id])->values()), employees: @js($employees->map(fn ($employee) => ['id' => $employee->id, 'name' => $employee->full_name, 'branch_id' => $employee->branch_id])->values()), organizationId: '{{ old('organization_id', $department->organization_id) }}', branchId: '{{ old('branch_id', $department->branch_id) }}' }">
                @csrf
                @method('PUT')
                <div class="card-body space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <x-input-label for="organization_id" value="Organization *" />
                            <select id="organization_id" name="organization_id" x-model="organizationId" class="select-field mt-1.5 block w-full" required>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('organization_id')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="branch_id" value="Branch *" />
                            <select id="branch_id" name="branch_id" x-model="branchId" class="select-field mt-1.5 block w-full" required>
                                <template x-for="branch in branches.filter((item) => String(item.organization_id) === String(organizationId))" :key="branch.id">
                                    <option :value="branch.id" x-text="branch.name"></option>
                                </template>
                            </select>
                            <x-input-error :messages="$errors->get('branch_id')" class="mt-1.5" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="name" value="Department Name *" />
                        <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name', $department->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                    </div>
                    <div>
                        <x-input-label for="code" value="Department Code" />
                        <x-text-input id="code" name="code" type="text" class="mt-1.5 block w-full" :value="old('code', $department->code)" />
                        <x-input-error :messages="$errors->get('code')" class="mt-1.5" />
                    </div>
                    </div>
                    <div>
                        <x-input-label for="department_head_id" value="Department Head" />
                        <select id="department_head_id" name="department_head_id" class="select-field mt-1.5 block w-full">
                            <option value="">Select Department Head</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" data-branch="{{ $employee->branch_id }}" {{ old('department_head_id', $department->department_head_id) == $employee->id ? 'selected' : '' }}>{{ $employee->full_name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('department_head_id')" class="mt-1.5" />
                    </div>
                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="3" class="textarea-field mt-1.5 block w-full">{{ old('description', $department->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1.5" />
                    </div>
                    <div>
                        <x-input-label for="status" value="Status *" />
                        <select id="status" name="status" class="select-field mt-1.5 block w-full">
                            <option value="active" {{ old('status', $department->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $department->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="card-header border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-3">
                    <a href="{{ route('departments.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                    <x-primary-button type="submit" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Update Department
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
