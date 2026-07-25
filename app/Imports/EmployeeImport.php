<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeImport implements ToCollection, WithHeadingRow, WithValidation
{
    private int $createdBy;

    private ?int $organizationId = null;

    private ?int $branchId = null;

    public function __construct(int $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
            'employee_number' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
        ];
    }

    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        $firstRow = $rows->first();
        $this->resolveOrganizationContext($firstRow);

        foreach ($rows as $row) {
            Employee::create([
                'organization_id' => $this->organizationId,
                'branch_id' => $this->branchId,
                'department_id' => $row['department_id'] ?? null,
                'designation_id' => $row['designation_id'] ?? null,
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'employee_number' => $row['employee_number'] ?? null,
                'phone' => $row['phone'] ?? null,
                'status' => 'active',
                'created_by' => $this->createdBy,
            ]);
        }
    }

    private function resolveOrganizationContext(array $row): void
    {
        $department = Department::find($row['department_id'] ?? null);
        $designation = Designation::find($row['designation_id'] ?? null);

        if ($department) {
            $this->organizationId = $department->organization_id;
            $this->branchId = $department->organization?->branches()->first()?->id;
        } elseif ($designation) {
            $this->organizationId = $designation->organization_id;
            $this->branchId = $designation->organization?->branches()->first()?->id;
        }

        if (! $this->organizationId) {
            $lastEmployee = Employee::orderByDesc('id')->first();
            if ($lastEmployee) {
                $this->organizationId = $lastEmployee->organization_id;
                $this->branchId = $lastEmployee->branch_id;
            }
        }
    }
}
