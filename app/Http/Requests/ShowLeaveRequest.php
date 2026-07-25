<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
