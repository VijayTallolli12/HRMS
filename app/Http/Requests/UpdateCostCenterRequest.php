<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCostCenterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-cost-center');
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('cost_centers')->where(fn ($q) => $q->where('organization_id', $this->route('cost_center')->organization_id))->ignore($this->route('cost_center')),
            ],
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Cost center code is required.',
            'code.unique' => 'A cost center with this code already exists in this organization.',
            'name.required' => 'Cost center name is required.',
            'department_id.exists' => 'The selected department does not exist.',
            'budget.numeric' => 'Budget must be a number.',
            'budget.min' => 'Budget must be at least 0.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be active or inactive.',
        ];
    }
}
