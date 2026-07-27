<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserManagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-user-management');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
            'organization_id' => 'nullable|exists:organizations,id',
            'branch_id' => 'nullable|exists:branches,id',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.required' => 'Role is required.',
            'role.exists' => 'The selected role is invalid.',
            'status.required' => 'Status is required.',
        ];
    }
}
