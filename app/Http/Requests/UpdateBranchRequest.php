<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-branch');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('branches')->where(fn ($q) => $q->where('organization_id', $this->branch->organization_id))->ignore($this->route('branch')),
            ],
            'address' => 'nullable|array',
            'phone' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
        ];
    }
}
