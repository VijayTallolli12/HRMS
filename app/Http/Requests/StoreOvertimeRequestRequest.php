<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOvertimeRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-overtime-request');
    }

    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0.5|max:24',
            'reason' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'organization_id.required' => 'Please select an organization.',
            'employee_id.required' => 'Please select an employee.',
            'date.required' => 'Date is required.',
            'date.date' => 'Please enter a valid date.',
            'hours.required' => 'Hours are required.',
            'hours.numeric' => 'Hours must be a number.',
            'hours.min' => 'Minimum overtime is 0.5 hours.',
            'hours.max' => 'Maximum overtime is 24 hours.',
            'reason.required' => 'Reason for overtime is required.',
        ];
    }
}
