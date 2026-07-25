<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-organization');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'tax_id' => ['nullable', 'string', 'max:100', Rule::unique('organizations', 'tax_id')->ignore($this->route('organization'))],
            'address' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ];
    }
}
