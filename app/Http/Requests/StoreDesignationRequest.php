<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Models\Department;
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
            'branch_id' => [
                'required',
                Rule::exists('branches', 'id')->where(fn ($q) => $q->where('organization_id', $this->organization_id)),
            ],
            'department_id' => [
                'required',
                Rule::exists('departments', 'id')->where(fn ($q) => $q->where('organization_id', $this->organization_id)->where('branch_id', $this->branch_id)),
            ],
            'title' => [
                'required', 'string', 'max:255',
                Rule::unique('designations')->where(fn ($q) => $q->where('organization_id', $this->organization_id)->where('branch_id', $this->branch_id)->where('department_id', $this->department_id)->whereNull('deleted_at')),
            ],
            'grade' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected function prepareForValidation(): void
    {
        $user = $this->user();
        $department = $this->input('department_id') ? Department::find($this->input('department_id')) : null;
        $branchId = $user->isBranchAdmin() ? $user->branch_id : ($this->input('branch_id') ?: $department?->branch_id);
        $branch = $branchId ? Branch::find($branchId) : null;

        $this->merge([
            'branch_id' => $branchId,
            'organization_id' => $user->isBranchAdmin() ? $user->organization_id : ($this->input('organization_id') ?: $branch?->organization_id ?: $department?->organization_id),
            'status' => $this->input('status', 'active'),
        ]);
    }
}
