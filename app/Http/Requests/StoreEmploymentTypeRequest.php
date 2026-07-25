<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmploymentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-employment-type');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:employment_types,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Employment type name is required.',
            'name.unique' => 'An employment type with this name already exists.',
        ];
    }
}
