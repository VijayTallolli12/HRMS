<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLatePolicyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-late-policy');
    }

    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('late_policies')->where(fn ($q) => $q->where('organization_id', $this->organization_id)),
            ],
            'grace_minutes' => 'required|integer|min:0|max:60',
            'max_late_per_month' => 'required|integer|min:1|max:31',
            'penalty_type' => 'required|in:warning,deduction,suspension',
            'penalty_amount' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'organization_id.required' => 'Please select an organization.',
            'name.required' => 'Policy name is required.',
            'name.unique' => 'A late policy with this name already exists in this organization.',
            'grace_minutes.required' => 'Grace minutes are required.',
            'grace_minutes.min' => 'Grace minutes cannot be negative.',
            'grace_minutes.max' => 'Grace minutes cannot exceed 60.',
            'max_late_per_month.required' => 'Max late per month is required.',
            'max_late_per_month.min' => 'Max late per month must be at least 1.',
            'penalty_type.required' => 'Penalty type is required.',
            'penalty_type.in' => 'Penalty type must be one of: warning, deduction, suspension.',
        ];
    }
}
