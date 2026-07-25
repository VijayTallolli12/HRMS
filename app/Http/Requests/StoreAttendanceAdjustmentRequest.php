<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-attendance-adjustment');
    }

    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'attendance_id' => 'required|exists:attendances,id',
            'employee_id' => 'required|exists:employees,id',
            'reason' => 'required|string|max:1000',
            'new_clock_in' => 'nullable|date_format:H:i',
            'new_clock_out' => 'nullable|date_format:H:i',
            'new_status' => 'required|in:present,absent,late,half-day,remote',
        ];
    }

    public function messages(): array
    {
        return [
            'organization_id.required' => 'Please select an organization.',
            'attendance_id.required' => 'Please select an attendance record.',
            'employee_id.required' => 'Please select an employee.',
            'reason.required' => 'Reason for adjustment is required.',
            'new_status.required' => 'New status is required.',
            'new_status.in' => 'Status must be one of: present, absent, late, half-day, remote.',
        ];
    }
}
