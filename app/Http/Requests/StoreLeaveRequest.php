<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-leave');
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'organization_id' => 'required|exists:organizations,id',
            'leave_type' => 'required|in:annual,sick,personal,unpaid,maternity,paternity',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'organization_id.required' => 'Please select an organization.',
            'leave_type.required' => 'Leave type is required.',
            'leave_type.in' => 'Leave type must be one of: annual, sick, personal, unpaid, maternity, paternity.',
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Please enter a valid start date.',
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'Please enter a valid end date.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'days.required' => 'Number of days is required.',
            'days.integer' => 'Days must be a whole number.',
            'days.min' => 'At least 1 day is required.',
        ];
    }
}
