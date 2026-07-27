<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Edit {{ $employee->first_name }} {{ $employee->last_name }}"
            icon="pencil-square"
            description="Update employee profile information."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('employees.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Employees</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Edit {{ $employee->first_name }}</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <form action="{{ route('employees.update', $employee) }}" method="POST" x-data="{ departments: @js($departments->map(fn ($department) => ['id' => $department->id, 'name' => $department->name, 'branch_id' => $department->branch_id])->values()), designations: @js($designations->map(fn ($designation) => ['id' => $designation->id, 'title' => $designation->title, 'department_id' => $designation->department_id])->values()), branchId: '{{ old('branch_id', $employee->branch_id) }}', departmentId: '{{ old('department_id', $employee->department_id) }}' }">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                {{-- Personal Information Section --}}
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50">
                                <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            </div>
                            <h3 class="text-section font-semibold text-gray-900">Personal Information</h3>
                        </div>
                    </div>
                    <div class="card-body space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="first_name" value="First Name *" />
                                <x-text-input id="first_name" name="first_name" type="text" class="mt-1.5 block w-full" :value="old('first_name', $employee->first_name)" required />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="last_name" value="Last Name *" />
                                <x-text-input id="last_name" name="last_name" type="text" class="mt-1.5 block w-full" :value="old('last_name', $employee->last_name)" required />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-1.5" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="employee_number" value="Employee Number" />
                                <x-text-input id="employee_number" name="employee_number" type="text" class="mt-1.5 block w-full" :value="old('employee_number', $employee->employee_number)" />
                                <x-input-error :messages="$errors->get('employee_number')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="email" value="Email" />
                                <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full" :value="old('email', $employee->email)" />
                                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1.5 block w-full" :value="old('phone', $employee->phone)" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="date_of_birth" value="Date of Birth" />
                                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1.5 block w-full" :value="old('date_of_birth', data_get($employee->meta, 'date_of_birth'))" />
                            </div>
                            <div>
                                <x-input-label for="gender" value="Gender" />
                                <select id="gender" name="gender" class="select-field mt-1.5 block w-full">
                                    <option value="">Select Gender</option>
                                    @foreach (['Female', 'Male', 'Non-binary', 'Prefer not to say'] as $gender)
                                        <option value="{{ $gender }}" {{ old('gender', data_get($employee->meta, 'gender')) === $gender ? 'selected' : '' }}>{{ $gender }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Employment Details Section --}}
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                            </div>
                            <h3 class="text-section font-semibold text-gray-900">Employment Details</h3>
                        </div>
                    </div>
                    <div class="card-body space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="branch_id" value="Branch" />
                                <input type="hidden" name="organization_id" value="{{ old('organization_id', $employee->organization_id) }}">
                                <select id="branch_id" name="branch_id" x-model="branchId" @change="departmentId = ''" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $br)
                                        <option value="{{ $br->id }}" {{ old('branch_id', $employee->branch_id) == $br->id ? 'selected' : '' }}>{{ $br->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="department_id" value="Department" />
                                <select id="department_id" name="department_id" x-model="departmentId" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}" x-show="String({{ $dept->branch_id ?? 0 }}) === String(branchId)" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="designation_id" value="Designation" />
                                <select id="designation_id" name="designation_id" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Designation</option>
                                    @foreach ($designations as $des)
                                        <option value="{{ $des->id }}" x-show="String({{ $des->department_id }}) === String(departmentId)" {{ old('designation_id', $employee->designation_id) == $des->id ? 'selected' : '' }}>{{ $des->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="hired_at" value="Joining Date" />
                                <x-text-input id="hired_at" name="hired_at" type="date" class="mt-1.5 block w-full" :value="old('hired_at', $employee->hired_at?->format('Y-m-d'))" />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="employment_type_id" value="Employment Type" />
                            <select id="employment_type_id" name="employment_type_id" class="select-field mt-1.5 block w-full">
                                <option value="">Select Employment Type</option>
                                @foreach ($employmentTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('employment_type_id', $employee->employment_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="status" value="Status *" />
                            <select id="status" name="status" class="select-field mt-1.5 block w-full">
                                <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="terminated" {{ old('status', $employee->status) === 'terminated' ? 'selected' : '' }}>Terminated</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="text-section font-semibold text-gray-900">Contact Information</h3></div>
                    <div class="card-body space-y-5">
                        <div>
                            <x-input-label for="address" value="Address" />
                            <textarea id="address" name="address" rows="3" class="textarea-field mt-1.5 block w-full">{{ old('address', data_get($employee->meta, 'address')) }}</textarea>
                        </div>
                        <div>
                            <x-input-label for="emergency_contact" value="Emergency Contact" />
                            <x-text-input id="emergency_contact" name="emergency_contact" type="text" class="mt-1.5 block w-full" :value="old('emergency_contact', data_get($employee->meta, 'emergency_contact'))" />
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="text-section font-semibold text-gray-900">Payroll Information</h3></div>
                    <div class="card-body grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach (['bank_name' => 'Bank Name', 'bank_account_number' => 'Bank Account Number', 'ifsc_code' => 'IFSC Code', 'pan_number' => 'PAN Number'] as $field => $label)
                            <div>
                                <x-input-label for="{{ $field }}" value="{{ $label }}" />
                                <x-text-input id="{{ $field }}" name="{{ $field }}" type="text" class="mt-1.5 block w-full" :value="old($field, data_get($employee->meta, $field))" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="text-section font-semibold text-gray-900">Reporting Manager</h3></div>
                    <div class="card-body">
                        @php($currentManagerId = $employee->reportingHierarchies()->where('reporting_type', 'primary')->where('is_active', true)->value('manager_id'))
                        <select id="reporting_manager_id" name="reporting_manager_id" class="select-field mt-1.5 block w-full">
                            <option value="">Select Reporting Manager</option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}" {{ old('reporting_manager_id', $currentManagerId) == $manager->id ? 'selected' : '' }}>{{ $manager->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="text-section font-semibold text-gray-900">Documents</h3></div>
                    <div class="card-body">
                        <textarea id="documents_note" name="documents_note" rows="3" class="textarea-field mt-1.5 block w-full">{{ old('documents_note', data_get($employee->meta, 'documents_note')) }}</textarea>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="text-section font-semibold text-gray-900">Attendance Settings</h3></div>
                    <div class="card-body grid grid-cols-1 md:grid-cols-2 gap-5">
                        @php($currentShiftId = $employee->shiftAssignments()->where('is_active', true)->latest('effective_from')->value('shift_id'))
                        <div>
                            <x-input-label for="shift_id" value="Shift" />
                            <select id="shift_id" name="shift_id" class="select-field mt-1.5 block w-full">
                                <option value="">Select Shift</option>
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ old('shift_id', $currentShiftId) == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="attendance_mode" value="Attendance Mode" />
                            <select id="attendance_mode" name="attendance_mode" class="select-field mt-1.5 block w-full">
                                <option value="">Select Mode</option>
                                @foreach (['biometric' => 'Biometric', 'web' => 'Web', 'manual' => 'Manual'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('attendance_mode', data_get($employee->meta, 'attendance_mode')) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('employees.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                    <x-primary-button type="submit" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Update Employee
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
