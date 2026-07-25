<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCostCenterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-cost-center');
    }

    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('cost_centers')->where(fn ($q) => $q->where('organization_id', $this->organization_id)),
            ],
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'budget' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'organization_id.required' => 'Please select an organization.',
            'organization_id.exists' => 'The selected organization does not exist.',
            'code.required' => 'Cost center code is required.',
            'code.unique' => 'A cost center with this code already exists in this organization.',
            'name.required' => 'Cost center name is required.',
            'department_id.exists' => 'The selected department does not exist.',
            'budget.numeric' => 'Budget must be a number.',
            'budget.min' => 'Budget must be at least 0.',
        ];
    }
}
