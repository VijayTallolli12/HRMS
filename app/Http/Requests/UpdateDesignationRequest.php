<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDesignationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-designation');
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required', 'string', 'max:255',
                Rule::unique('designations')->where(fn ($q) => $q->where('organization_id', $this->designation->organization_id)->where('department_id', $this->designation->department_id))->ignore($this->route('designation')),
            ],
            'level' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ];
    }
}
