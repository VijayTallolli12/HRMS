<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeExport implements FromCollection, WithHeadings, WithMapping
{
    private bool $template;

    private int $row = 0;

    public function __construct(bool $template = false)
    {
        $this->template = $template;
    }

    public function collection(): Collection
    {
        if ($this->template) {
            return collect();
        }

        return Employee::with(['organization', 'branch', 'department', 'designation'])
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Email',
            'Employee Number',
            'Phone',
            'Department',
            'Designation',
            'Branch',
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
            $employee->phone ?? '',
            $employee->department->name ?? '',
            $employee->designation->title ?? '',
            $employee->branch->name ?? '',
            ucfirst($employee->status),
            $employee->hired_at?->format('Y-m-d') ?? '',
        ];
    }
}
