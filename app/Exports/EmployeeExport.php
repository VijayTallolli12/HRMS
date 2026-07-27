<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeExport implements FromCollection, WithHeadings, WithMapping
{
    private bool $template = false;

    private ?User $user = null;

    private int $row = 0;

    public function __construct(bool|User $template = false)
    {
        if ($template instanceof User) {
            $this->user = $template;

            return;
        }

        $this->template = $template;
    }

    public function collection(): Collection
    {
        if ($this->template) {
            return collect();
        }

        $query = Employee::with(['organization', 'branch', 'department', 'designation', 'employmentType']);

        if ($this->user?->isBranchAdmin()) {
            $query->where('branch_id', $this->user->branch_id);
        }

        return $query->orderBy('id')->get();
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Email',
            'Employee Number',
            'Branch',
            'Phone',
            'Department',
            'Designation',
            'Employment Type',
            'Status',
            'Hire Date',
        ];
    }

    public function map($employee): array
    {
        $this->row++;

        return [
            $employee->first_name,
            $employee->last_name,
            $employee->email,
            $employee->employee_number ?? '',
            $employee->branch->name ?? '',
            $employee->phone ?? '',
            $employee->department->name ?? '',
            $employee->designation->title ?? '',
            $employee->employmentType->name ?? '',
            ucfirst($employee->status),
            $employee->hired_at?->format('Y-m-d') ?? '',
        ];
    }
}
