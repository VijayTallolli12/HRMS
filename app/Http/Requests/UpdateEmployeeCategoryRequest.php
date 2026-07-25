<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-employee-category');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:employee_categories,name,'.$this->route('employee_category')->id,
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Employee category name is required.',
            'name.unique' => 'An employee category with this name already exists.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be active or inactive.',
        ];
    }
}
