<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-employee-category');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:employee_categories,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Employee category name is required.',
            'name.unique' => 'An employee category with this name already exists.',
        ];
    }
}
