<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWeekendPolicyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-weekend-policy');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('weekend_policies')->where(fn ($q) => $q->where('organization_id', $this->route('weekend_policy')->organization_id))->ignore($this->route('weekend_policy')),
            ],
            'weekend_days' => 'required|array',
            'weekend_days.*' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Weekend policy name is required.',
            'name.unique' => 'A weekend policy with this name already exists in this organization.',
            'weekend_days.required' => 'Weekend days are required.',
            'weekend_days.array' => 'Weekend days must be an array.',
            'weekend_days.*.required' => 'Each weekend day is required.',
            'weekend_days.*.in' => 'Invalid day name. Must be Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, or Sunday.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be active or inactive.',
        ];
    }
}
