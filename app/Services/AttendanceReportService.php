<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\MissingPunch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AttendanceReportService
{
    public function getMonthlySummary(int $year, int $month, ?int $branchId = null, ?int $departmentId = null): Collection
    {
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $daysInMonth = $startOfMonth->daysInMonth;

        $query = Employee::where('status', 'active')
            ->with(['branch', 'department', 'designation']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $employees = $query->get();

        $attendanceData = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->when($branchId, fn ($q) => $q->whereHas('employee', fn ($eq) => $eq->where('branch_id', $branchId)))
            ->get()
            ->groupBy('employee_id');

        return $employees->map(function ($employee) use ($attendanceData, $daysInMonth, $startOfMonth) {
            $records = $attendanceData->get($employee->id, collect());
            $dailyStatus = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = $startOfMonth->copy()->addDays($day - 1)->format('Y-m-d');
                $record = $records->firstWhere('date', $date);
                $dailyStatus[$day] = $record ? [
                    'status' => $record->status,
                    'clock_in' => $record->clock_in,
                    'clock_out' => $record->clock_out,
                    'hours_worked' => $record->hours_worked,
                ] : null;
            }

            $present = $records->where('status', 'present')->count();
            $absent = $daysInMonth - $records->count();
            $late = $records->where('status', 'late')->count();
            $halfDay = $records->where('status', 'half-day')->count();
            $totalHours = $records->sum('hours_worked');

            return [
                'employee' => $employee,
                'daily' => $dailyStatus,
                'summary' => [
                    'present' => $present,
                    'absent' => max(0, $absent),
                    'late' => $late,
                    'half_day' => $halfDay,
                    'total_hours' => round($totalHours, 2),
                    'total_days' => $daysInMonth,
                ],
            ];
        });
    }

    public function getAttendanceReport(
        string $dateFrom,
        string $dateTo,
        ?int $branchId = null,
        ?int $departmentId = null,
    ): Collection {
        return Employee::where('status', 'active')
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->when($departmentId, fn ($q) => $q->where('department_id', $departmentId))
            ->with(['branch', 'department'])
            ->get()
            ->map(function ($employee) use ($dateFrom, $dateTo) {
                $attendances = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$dateFrom, $dateTo])
                    ->get();

                $totalDays = Carbon::parse($dateFrom)->diffInDays(Carbon::parse($dateTo)) + 1;
                $present = $attendances->where('status', 'present')->count();
                $late = $attendances->where('status', 'late')->count();
                $absent = $totalDays - $attendances->count();
                $totalHours = $attendances->sum('hours_worked');

                return [
                    'employee' => $employee,
                    'total_days' => $totalDays,
                    'present' => $present,
                    'late' => $late,
                    'absent' => max(0, $absent),
                    'half_day' => $attendances->where('status', 'half-day')->count(),
                    'total_hours' => round($totalHours, 2),
                    'attendance_rate' => $totalDays > 0 ? round(($present / $totalDays) * 100) : 0,
                ];
            });
    }

    public function getLateReport(
        string $dateFrom,
        string $dateTo,
        ?int $branchId = null,
    ): Collection {
        return Attendance::where('status', 'late')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->when($branchId, fn ($q) => $q->whereHas('employee', fn ($eq) => $eq->where('branch_id', $branchId)))
            ->with(['employee.branch', 'employee.department'])
            ->orderBy('date')
            ->get()
            ->groupBy('employee_id')
            ->map(function ($records, $employeeId) {
                $employee = $records->first()->employee;

                return [
                    'employee' => $employee,
                    'late_count' => $records->count(),
                    'total_late_minutes' => $records->sum('late_minutes'),
                    'records' => $records,
                ];
            })
            ->values();
    }

    public function getMissingPunchReport(?int $branchId = null, ?string $dateFrom = null, ?string $dateTo = null): Collection
    {
        return MissingPunch::where('status', 'pending')
            ->when($branchId, fn ($q) => $q->whereHas('employee', fn ($eq) => $eq->where('branch_id', $branchId)))
            ->when($dateFrom, fn ($q) => $q->where('date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->where('date', '<=', $dateTo))
            ->with(['employee.branch', 'employee.department'])
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getAttendanceStats(int $organizationId, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $query = Attendance::where('organization_id', $organizationId);

        if ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('date', '<=', $dateTo);
        }

        $records = $query->get();

        return [
            'total_records' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'late' => $records->where('status', 'late')->count(),
            'half_day' => $records->where('status', 'half-day')->count(),
            'remote' => $records->where('status', 'remote')->count(),
            'total_hours' => round($records->sum('hours_worked'), 2),
            'total_overtime' => round($records->sum('overtime_hours'), 2),
        ];
    }
}
