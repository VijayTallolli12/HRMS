<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHolidayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-holiday');
    }

    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('holidays')->where(fn ($q) => $q->where('organization_id', $this->organization_id)),
            ],
            'date' => 'required|date',
            'type' => 'required|in:public,optional,company',
        ];
    }

    public function messages(): array
    {
        return [
            'organization_id.required' => 'Please select an organization.',
            'organization_id.exists' => 'The selected organization does not exist.',
            'name.required' => 'Holiday name is required.',
            'name.unique' => 'A holiday with this name already exists in this organization.',
            'date.required' => 'Holiday date is required.',
            'date.date' => 'Please enter a valid date.',
            'type.required' => 'Holiday type is required.',
            'type.in' => 'Holiday type must be public, optional, or company.',
        ];
    }
}
