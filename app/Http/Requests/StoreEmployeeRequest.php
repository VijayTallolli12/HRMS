<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-employee');
    }

    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'branch_id' => [
                'required',
                Rule::exists('branches', 'id')->where(fn ($q) => $q->where('organization_id', $this->organization_id)),
            ],
            'department_id' => [
                'required',
                Rule::exists('departments', 'id')->where(fn ($q) => $q->where('organization_id', $this->organization_id)->where('branch_id', $this->branch_id)),
            ],
            'designation_id' => [
                'required',
                Rule::exists('designations', 'id')->where(fn ($q) => $q->where('organization_id', $this->organization_id)->where('branch_id', $this->branch_id)->where('department_id', $this->department_id)),
            ],
            'employment_type_id' => 'nullable|exists:employment_types,id',
            'employee_category_id' => 'nullable|exists:employee_categories,id',
            'employment_status_id' => 'nullable|exists:employment_statuses,id',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_number' => 'nullable|string|max:50|unique:employees,employee_number',
            'email' => 'nullable|email|max:255|unique:employees,email',
            'phone' => 'nullable|string|max:50',
            'hired_at' => 'nullable|date',
            'reporting_manager_id' => [
                'nullable',
                Rule::exists('employees', 'id')->where(fn ($q) => $q->where('organization_id', $this->organization_id)->where('branch_id', $this->branch_id)),
            ],
            'shift_id' => [
                'nullable',
                Rule::exists('shifts', 'id')->where(fn ($q) => $q->where('organization_id', $this->organization_id)),
            ],
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
            'emergency_contact' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:100',
            'ifsc_code' => 'nullable|string|max:50',
            'pan_number' => 'nullable|string|max:50',
            'uan_number' => 'nullable|string|max:50',
            'documents_note' => 'nullable|string|max:1000',
            'attendance_mode' => 'nullable|string|max:100',
        ];
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();
        $department = $this->input('department_id') ? Department::find($this->input('department_id')) : null;
        $branchId = $user->isBranchAdmin() ? $user->branch_id : ($this->input('branch_id') ?: $department?->branch_id);
        $branch = $branchId ? Branch::find($branchId) : null;

        $this->merge([
            'branch_id' => $branchId,
            'organization_id' => $user->isBranchAdmin() ? $user->organization_id : ($this->input('organization_id') ?: $branch?->organization_id ?: $department?->organization_id),
        ]);
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'branch_id.required' => 'Branch is required.',
            'department_id.required' => 'Department is required.',
            'designation_id.required' => 'Designation is required.',
            'employee_number.unique' => 'This employee number is already taken.',
            'email.unique' => 'This email is already registered for another employee.',
        ];
    }
}
