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

    private bool $previewOnly = false;

    private array $previewRows = [];

    private array $importSummary = [
        'created' => 0,
        'updated' => 0,
        'skipped' => 0,
        'errors' => [],
    ];

    private bool $skipDuplicates = false;

    private bool $updateExisting = false;

    public function __construct(int $createdBy, array $options = [])
    {
        $this->createdBy = $createdBy;
        $this->previewOnly = $options['preview'] ?? false;
        $this->skipDuplicates = $options['skip_duplicates'] ?? false;
        $this->updateExisting = $options['update_existing'] ?? false;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
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

        foreach ($rows as $index => $row) {
            $email = $row['email'] ?? null;
            $empNumber = $row['employee_number'] ?? null;

            $existing = null;
            if ($email) {
                $existing = Employee::where('email', $email)->first();
            }
            if (! $existing && $empNumber) {
                $existing = Employee::where('employee_number', $empNumber)->first();
            }

            $rowData = [
                'row' => $index + 2,
                'first_name' => $row['first_name'] ?? '',
                'last_name' => $row['last_name'] ?? '',
                'email' => $email,
                'employee_number' => $empNumber,
                'phone' => $row['phone'] ?? null,
                'department_id' => $row['department_id'] ?? null,
                'designation_id' => $row['designation_id'] ?? null,
                'status' => 'duplicate',
            ];

            if ($this->previewOnly) {
                if ($existing) {
                    $rowData['status'] = 'duplicate';
                    $rowData['existing_id'] = $existing->id;
                } else {
                    $rowData['status'] = 'new';
                }
                $this->previewRows[] = $rowData;
                continue;
            }

            if ($existing) {
                if ($this->skipDuplicates) {
                    $this->importSummary['skipped']++;
                    continue;
                }
                if ($this->updateExisting) {
                    $existing->update([
                        'first_name' => $row['first_name'] ?? $existing->first_name,
                        'last_name' => $row['last_name'] ?? $existing->last_name,
                        'email' => $email ?? $existing->email,
                        'phone' => $row['phone'] ?? $existing->phone,
                        'department_id' => $row['department_id'] ?? $existing->department_id,
                        'designation_id' => $row['designation_id'] ?? $existing->designation_id,
                    ]);
                    $this->importSummary['updated']++;
                    continue;
                }
                $this->importSummary['skipped']++;
                continue;
            }

            Employee::create([
                'organization_id' => $this->organizationId,
                'branch_id' => $this->branchId,
                'department_id' => $row['department_id'] ?? null,
                'designation_id' => $row['designation_id'] ?? null,
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $email,
                'employee_number' => $empNumber,
                'phone' => $row['phone'] ?? null,
                'status' => 'active',
                'created_by' => $this->createdBy,
            ]);
            $this->importSummary['created']++;
        }
    }

    public function getPreviewRows(): array
    {
        return $this->previewRows;
    }

    public function getImportSummary(): array
    {
        return $this->importSummary;
    }

    private function resolveOrganizationContext(array $row): void
    {
        $department = Department::find($row['department_id'] ?? null);
        $designation = Designation::find($row['designation_id'] ?? null);

        if ($department) {
            $this->organizationId = $department->organization_id;
            $this->branchId = $department->branch_id;
        } elseif ($designation) {
            $this->organizationId = $designation->organization_id;
            $this->branchId = $designation->branch_id;
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
