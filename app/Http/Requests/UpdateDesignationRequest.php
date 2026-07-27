<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Models\Department;
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
        $designation = $this->route('designation');

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
                Rule::unique('designations')->where(fn ($q) => $q->where('organization_id', $this->organization_id)->where('branch_id', $this->branch_id)->where('department_id', $this->department_id)->whereNull('deleted_at'))->ignore($designation),
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
        $designation = $this->route('designation');
        $department = $this->input('department_id') ? Department::find($this->input('department_id')) : null;
        $branchId = $user->isBranchAdmin() ? $user->branch_id : ($this->input('branch_id') ?: $department?->branch_id ?: $designation->branch_id);
        $branch = $branchId ? Branch::find($branchId) : null;

        $this->merge([
            'branch_id' => $branchId,
            'department_id' => $this->input('department_id', $designation->department_id),
            'organization_id' => $user->isBranchAdmin() ? $user->organization_id : ($this->input('organization_id', $designation->organization_id) ?: $branch?->organization_id ?: $department?->organization_id),
        ]);
    }
}
