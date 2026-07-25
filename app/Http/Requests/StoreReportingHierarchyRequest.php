<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportingHierarchyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-reporting-hierarchy');
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'manager_id' => 'required|exists:employees,id',
            'reporting_type' => 'required|in:direct,functional,administrative',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->employee_id && $this->manager_id && $this->employee_id === $this->manager_id) {
                $validator->errors()->add('manager_id', 'Employee and manager cannot be the same person.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'manager_id.required' => 'Please select a manager.',
            'manager_id.exists' => 'The selected manager does not exist.',
            'reporting_type.required' => 'Reporting type is required.',
            'reporting_type.in' => 'Reporting type must be direct, functional, or administrative.',
            'effective_from.required' => 'Effective from date is required.',
            'effective_from.date' => 'Please enter a valid date.',
            'effective_to.after_or_equal' => 'Effective to must be after or equal to effective from.',
        ];
    }
}
