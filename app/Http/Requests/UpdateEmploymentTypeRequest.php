<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmploymentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-employment-type');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:employment_types,name,'.$this->route('employment_type')->id,
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Employment type name is required.',
            'name.unique' => 'An employment type with this name already exists.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be active or inactive.',
        ];
    }
}
