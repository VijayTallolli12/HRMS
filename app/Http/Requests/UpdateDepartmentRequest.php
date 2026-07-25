<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-department');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('departments')->where(fn ($q) => $q->where('organization_id', $this->department->organization_id))->ignore($this->route('department')),
            ],
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ];
    }
}
