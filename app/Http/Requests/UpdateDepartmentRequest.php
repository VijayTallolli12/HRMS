<?php

namespace App\Http\Requests;

use App\Models\Branch;
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
        $department = $this->route('department');

        return [
            'organization_id' => 'required|exists:organizations,id',
            'branch_id' => [
                'required',
                Rule::exists('branches', 'id')->where(fn ($q) => $q->where('organization_id', $this->organization_id)),
            ],
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('departments')->where(fn ($q) => $q->where('organization_id', $this->organization_id)->where('branch_id', $this->branch_id)->whereNull('deleted_at'))->ignore($department),
            ],
            'code' => 'nullable|string|max:50',
            'department_head_id' => [
                'nullable',
                Rule::exists('employees', 'id')->where(fn ($q) => $q->where('organization_id', $this->organization_id)->where('branch_id', $this->branch_id)),
            ],
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();
        $department = $this->route('department');
        $branchId = $user->isBranchAdmin() ? $user->branch_id : $this->input('branch_id', $department->branch_id);
        $branch = $branchId ? Branch::find($branchId) : null;

        $this->merge([
            'branch_id' => $branchId,
            'organization_id' => $user->isBranchAdmin() ? $user->organization_id : ($this->input('organization_id', $department->organization_id) ?: $branch?->organization_id),
        ]);
    }
}
