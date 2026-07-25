<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'branch_id' => 'nullable|exists:branches,id',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_number' => 'nullable|string|max:50|unique:employees,employee_number',
            'email' => 'nullable|email|max:255|unique:employees,email',
            'phone' => 'nullable|string|max:50',
            'hired_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'employee_number.unique' => 'This employee number is already taken.',
            'email.unique' => 'This email is already registered for another employee.',
        ];
    }
}
