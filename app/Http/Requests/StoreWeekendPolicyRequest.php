<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWeekendPolicyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-weekend-policy');
    }

    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('weekend_policies')->where(fn ($q) => $q->where('organization_id', $this->organization_id)),
            ],
            'weekend_days' => 'required|array',
            'weekend_days.*' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ];
    }

    public function messages(): array
    {
        return [
            'organization_id.required' => 'Please select an organization.',
            'organization_id.exists' => 'The selected organization does not exist.',
            'name.required' => 'Weekend policy name is required.',
            'name.unique' => 'A weekend policy with this name already exists in this organization.',
            'weekend_days.required' => 'Weekend days are required.',
            'weekend_days.array' => 'Weekend days must be an array.',
            'weekend_days.*.required' => 'Each weekend day is required.',
            'weekend_days.*.in' => 'Invalid day name. Must be Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, or Sunday.',
        ];
    }
}
