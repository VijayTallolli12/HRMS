<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
