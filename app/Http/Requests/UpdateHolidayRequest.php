<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHolidayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-holiday');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('holidays')->where(fn ($q) => $q->where('organization_id', $this->route('holiday')->organization_id))->ignore($this->route('holiday')),
            ],
            'date' => 'required|date',
            'type' => 'required|in:public,optional,company',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Holiday name is required.',
            'name.unique' => 'A holiday with this name already exists in this organization.',
            'date.required' => 'Holiday date is required.',
            'date.date' => 'Please enter a valid date.',
            'type.required' => 'Holiday type is required.',
            'type.in' => 'Holiday type must be public, optional, or company.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be active or inactive.',
        ];
    }
}
