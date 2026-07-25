<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-work-schedule');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('work_schedules')->where(fn ($q) => $q->where('organization_id', $this->route('work_schedule')->organization_id))->ignore($this->route('work_schedule')),
            ],
            'working_days' => 'required|array',
            'hours_per_day' => 'required|numeric|min:0.5|max:24',
            'break_minutes' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Work schedule name is required.',
            'name.unique' => 'A work schedule with this name already exists in this organization.',
            'working_days.required' => 'Working days are required.',
            'working_days.array' => 'Working days must be an array.',
            'hours_per_day.required' => 'Hours per day is required.',
            'hours_per_day.numeric' => 'Hours per day must be a number.',
            'hours_per_day.min' => 'Hours per day must be at least 0.5.',
            'hours_per_day.max' => 'Hours per day cannot exceed 24.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be active or inactive.',
        ];
    }
}
