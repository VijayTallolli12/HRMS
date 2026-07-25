<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmploymentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-employment-status');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:employment_statuses,name,'.$this->route('employment_status')->id,
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Employment status name is required.',
            'name.unique' => 'An employment status with this name already exists.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be active or inactive.',
        ];
    }
}
