<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmploymentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-employment-status');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:employment_statuses,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Employment status name is required.',
            'name.unique' => 'An employment status with this name already exists.',
        ];
    }
}
