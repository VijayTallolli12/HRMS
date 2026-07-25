<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AttendanceImport implements ToCollection, WithHeadingRow, WithValidation
{
    private int $createdBy;

    public function __construct(int $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'date' => ['required', 'date'],
            'clock_in' => ['nullable', 'date_format:H:i'],
            'clock_out' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'string', 'in:present,absent,late,half-day,remote'],
        ];
    }

    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        foreach ($rows as $row) {
            $employee = Employee::find($row['employee_id']);
            $organizationId = $employee?->organization_id;

            $hoursWorked = null;
            $overtimeHours = null;

            if ($row['clock_in'] && $row['clock_out']) {
                $clockIn = Carbon::parse($row['date'].' '.$row['clock_in']);
                $clockOut = Carbon::parse($row['date'].' '.$row['clock_out']);
                $diff = $clockIn->diffInHours($clockOut, asFloat: true);
                $hoursWorked = round(max(0, $diff), 2);

                if ($hoursWorked > 8) {
                    $overtimeHours = round($hoursWorked - 8, 2);
                }
            }

            Attendance::create([
                'organization_id' => $organizationId,
                'employee_id' => $row['employee_id'],
                'date' => $row['date'],
                'clock_in' => $row['clock_in'] ?? null,
                'clock_out' => $row['clock_out'] ?? null,
                'status' => $row['status'],
                'hours_worked' => $hoursWorked,
                'overtime_hours' => $overtimeHours,
                'created_by' => $this->createdBy,
            ]);
        }
    }
}
