<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResolveMissingPunchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-attendance');
    }

    public function rules(): array
    {
        return [
            'correct_time' => 'required|date_format:H:i',
            'resolution_notes' => 'nullable|string|max:500',
        ];
    }
}
