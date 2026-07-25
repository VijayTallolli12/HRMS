<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-attendance');
    }

    public function rules(): array
    {
        return [
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after_or_equal:clock_in',
            'status' => 'required|in:present,absent,late,half-day,remote',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'clock_in.date_format' => 'Clock in must be in HH:MM format.',
            'clock_out.date_format' => 'Clock out must be in HH:MM format.',
            'clock_out.after_or_equal' => 'Clock out must be after or equal to clock in.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: present, absent, late, half-day, remote.',
        ];
    }
}
