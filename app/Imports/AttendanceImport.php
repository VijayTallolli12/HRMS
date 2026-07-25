<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AttendanceImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function __construct(
        private readonly int $createdBy,
    ) {}

    public function collection($rows)
    {
        foreach ($rows as $row) {
            $employee = Employee::where('employee_id', $row['employee_code'] ?? $row['employee_id'] ?? '')
                ->first();

            if (! $employee) {
                continue;
            }

            $clockIn = $row['clock_in'] ?? null;
            $clockOut = $row['clock_out'] ?? null;
            $hoursWorked = 0;

            if ($clockIn && $clockOut) {
                $start = strtotime($clockIn);
                $end = strtotime($clockOut);
                if ($end <= $start) {
                    $end += 86400;
                }
                $hoursWorked = round(($end - $start) / 3600, 2);
            }

            Attendance::updateOrCreate(
                ['employee_id' => $employee->id, 'date' => $row['date']],
                [
                    'organization_id' => $employee->organization_id,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'status' => $row['status'] ?? 'present',
                    'hours_worked' => $hoursWorked,
                    'overtime_hours' => max(0, $hoursWorked - 8),
                    'source' => 'import',
                    'created_by' => $this->createdBy,
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            'employee_code' => 'required|string',
            'employee_id' => 'required_without:employee_code|string',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half-day,remote',
        ];
    }
}
