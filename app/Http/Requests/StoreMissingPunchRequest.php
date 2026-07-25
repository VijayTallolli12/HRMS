<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMissingPunchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('view-attendance');
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'punch_type' => 'required|in:missing_in,missing_out',
            'notes' => 'nullable|string|max:500',
        ];
    }
}
