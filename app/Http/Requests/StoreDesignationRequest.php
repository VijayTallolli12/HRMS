<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDesignationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-designation');
    }

    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'department_id' => 'required|exists:departments,id',
            'title' => [
                'required', 'string', 'max:255',
                Rule::unique('designations')->where(fn ($q) => $q->where('organization_id', $this->organization_id)->where('department_id', $this->department_id)),
            ],
            'level' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:1000',
        ];
    }
}
