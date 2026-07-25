<?php

namespace App\Http\Requests;

use App\Models\LatePolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLatePolicyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-late-policy');
    }

    public function prepareForValidation(): void
    {
        $latePolicyId = $this->route('late_policy');
        if ($latePolicyId && ! $latePolicyId instanceof LatePolicy) {
            $latePolicy = LatePolicy::find($latePolicyId);
            if ($latePolicy) {
                $this->merge(['_late_policy_org_id' => $latePolicy->organization_id]);
            }
        }
    }

    public function rules(): array
    {
        $latePolicyId = $this->route('late_policy');
        $orgId = $this->_late_policy_org_id ?? ($latePolicyId instanceof LatePolicy ? $latePolicyId->organization_id : null);

        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('late_policies')->where(fn ($q) => $q->where('organization_id', $orgId))->ignore($latePolicyId),
            ],
            'grace_minutes' => 'required|integer|min:0|max:60',
            'max_late_per_month' => 'required|integer|min:1|max:31',
            'penalty_type' => 'required|in:warning,deduction,suspension',
            'penalty_amount' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Policy name is required.',
            'name.unique' => 'A late policy with this name already exists in this organization.',
            'grace_minutes.required' => 'Grace minutes are required.',
            'max_late_per_month.required' => 'Max late per month is required.',
            'penalty_type.required' => 'Penalty type is required.',
            'is_active.required' => 'Status is required.',
        ];
    }
}
