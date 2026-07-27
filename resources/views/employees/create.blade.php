<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Add Employee"
            icon="user-plus"
            description="Create a new employee profile in your organization."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('employees.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Employees</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Add Employee</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <form action="{{ route('employees.store') }}" method="POST" x-data="{ departments: @js($departments->map(fn ($department) => ['id' => $department->id, 'name' => $department->name, 'branch_id' => $department->branch_id])->values()), designations: @js($designations->map(fn ($designation) => ['id' => $designation->id, 'title' => $designation->title, 'department_id' => $designation->department_id])->values()), branchId: '{{ old('branch_id') }}', departmentId: '{{ old('department_id') }}' }">
            @csrf
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
                                <x-text-input id="first_name" name="first_name" type="text" class="mt-1.5 block w-full" :value="old('first_name')" required autofocus />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="last_name" value="Last Name *" />
                                <x-text-input id="last_name" name="last_name" type="text" class="mt-1.5 block w-full" :value="old('last_name')" required />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-1.5" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="employee_number" value="Employee Number" />
                                <x-text-input id="employee_number" name="employee_number" type="text" class="mt-1.5 block w-full" :value="old('employee_number', $nextEmployeeNumber)" />
                                <x-input-error :messages="$errors->get('employee_number')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="email" value="Email" />
                                <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full" :value="old('email')" />
                                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1.5 block w-full" :value="old('phone')" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="date_of_birth" value="Date of Birth" />
                                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1.5 block w-full" :value="old('date_of_birth')" />
                                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="gender" value="Gender" />
                                <select id="gender" name="gender" class="select-field mt-1.5 block w-full">
                                    <option value="">Select Gender</option>
                                    @foreach (['Female', 'Male', 'Non-binary', 'Prefer not to say'] as $gender)
                                        <option value="{{ $gender }}" {{ old('gender') === $gender ? 'selected' : '' }}>{{ $gender }}</option>
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
                                <x-input-label for="organization_id" value="Organization *" />
                                <select id="organization_id" name="organization_id" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Organization</option>
                                    @foreach ($organizations as $org)
                                        <option value="{{ $org->id }}" {{ old('organization_id', $selectedOrgId ?? '') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('organization_id')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="branch_id" value="Branch" />
                                <select id="branch_id" name="branch_id" x-model="branchId" @change="departmentId = ''" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $br)
                                        <option value="{{ $br->id }}" {{ old('branch_id') == $br->id ? 'selected' : '' }}>{{ $br->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="department_id" value="Department" />
                                <select id="department_id" name="department_id" x-model="departmentId" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Department</option>
                                    <template x-for="department in departments.filter((item) => String(item.branch_id) === String(branchId))" :key="department.id">
                                        <option :value="department.id" x-text="department.name"></option>
                                    </template>
                                </select>
                                <x-input-error :messages="$errors->get('department_id')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="designation_id" value="Designation" />
                                <select id="designation_id" name="designation_id" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Designation</option>
                                    <template x-for="designation in designations.filter((item) => String(item.department_id) === String(departmentId))" :key="designation.id">
                                        <option :value="designation.id" x-text="designation.title"></option>
                                    </template>
                                </select>
                                <x-input-error :messages="$errors->get('designation_id')" class="mt-1.5" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                            <x-input-label for="hired_at" value="Joining Date" />
                            <x-text-input id="hired_at" name="hired_at" type="date" class="mt-1.5 block w-full" :value="old('hired_at')" />
                            </div>
                            <div>
                                <x-input-label for="employment_type_id" value="Employment Type" />
                                <select id="employment_type_id" name="employment_type_id" class="select-field mt-1.5 block w-full">
                                    <option value="">Select Employment Type</option>
                                    @foreach ($employmentTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('employment_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="employee_category_id" value="Employee Category" />
                                <select id="employee_category_id" name="employee_category_id" class="select-field mt-1.5 block w-full">
                                    <option value="">Select Category</option>
                                    @foreach ($employeeCategories as $category)
                                        <option value="{{ $category->id }}" {{ old('employee_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="employment_status_id" value="Employment Status" />
                                <select id="employment_status_id" name="employment_status_id" class="select-field mt-1.5 block w-full">
                                    <option value="">Select Status</option>
                                    @foreach ($employmentStatuses as $status)
                                        <option value="{{ $status->id }}" {{ old('employment_status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <x-input-label for="cost_center_id" value="Cost Center" />
                            <select id="cost_center_id" name="cost_center_id" class="select-field mt-1.5 block w-full">
                                <option value="">Select Cost Center</option>
                                @foreach ($costCenters as $center)
                                    <option value="{{ $center->id }}" {{ old('cost_center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="text-section font-semibold text-gray-900">Contact Information</h3></div>
                    <div class="card-body space-y-5">
                        <div>
                            <x-input-label for="address" value="Address" />
                            <textarea id="address" name="address" rows="3" class="textarea-field mt-1.5 block w-full">{{ old('address') }}</textarea>
                        </div>
                        <div>
                            <x-input-label for="emergency_contact" value="Emergency Contact" />
                            <x-text-input id="emergency_contact" name="emergency_contact" type="text" class="mt-1.5 block w-full" :value="old('emergency_contact')" />
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="text-section font-semibold text-gray-900">Payroll Information</h3></div>
                    <div class="card-body grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <x-input-label for="bank_name" value="Bank Name" />
                            <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1.5 block w-full" :value="old('bank_name')" />
                        </div>
                        <div>
                            <x-input-label for="bank_account_number" value="Bank Account Number" />
                            <x-text-input id="bank_account_number" name="bank_account_number" type="text" class="mt-1.5 block w-full" :value="old('bank_account_number')" />
                        </div>
                        <div>
                            <x-input-label for="ifsc_code" value="IFSC Code" />
                            <x-text-input id="ifsc_code" name="ifsc_code" type="text" class="mt-1.5 block w-full" :value="old('ifsc_code')" />
                        </div>
                        <div>
                            <x-input-label for="pan_number" value="PAN Number" />
                            <x-text-input id="pan_number" name="pan_number" type="text" class="mt-1.5 block w-full" :value="old('pan_number')" />
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="text-section font-semibold text-gray-900">Reporting Manager</h3></div>
                    <div class="card-body">
                        <select id="reporting_manager_id" name="reporting_manager_id" class="select-field mt-1.5 block w-full">
                            <option value="">Select Reporting Manager</option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}" {{ old('reporting_manager_id') == $manager->id ? 'selected' : '' }}>{{ $manager->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="text-section font-semibold text-gray-900">Documents</h3></div>
                    <div class="card-body">
                        <textarea id="documents_note" name="documents_note" rows="3" class="textarea-field mt-1.5 block w-full" placeholder="Document checklist or notes">{{ old('documents_note') }}</textarea>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h3 class="text-section font-semibold text-gray-900">Attendance Settings</h3></div>
                    <div class="card-body grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <x-input-label for="shift_id" value="Shift" />
                            <select id="shift_id" name="shift_id" class="select-field mt-1.5 block w-full">
                                <option value="">Select Shift</option>
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="attendance_mode" value="Attendance Mode" />
                            <select id="attendance_mode" name="attendance_mode" class="select-field mt-1.5 block w-full">
                                <option value="">Select Mode</option>
                                <option value="biometric" {{ old('attendance_mode') === 'biometric' ? 'selected' : '' }}>Biometric</option>
                                <option value="web" {{ old('attendance_mode') === 'web' ? 'selected' : '' }}>Web</option>
                                <option value="manual" {{ old('attendance_mode') === 'manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('employees.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                    <x-primary-button type="submit" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Add Employee
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
