<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-attendance');
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|max:10240|mimes:xlsx,xls,csv,txt',
            'organization_id' => 'required|exists:organizations,id',
            'branch_id' => 'nullable|exists:branches,id',
        ];
    }
}
