<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Organization;
use Carbon\Carbon;

class DashboardService
{
    public function getDashboardData(bool $isSuperAdmin, ?int $branchId): array
    {
        if ($isSuperAdmin) {
            return $this->getSuperAdminData();
        }

        return $this->getBranchAdminData($branchId);
    }

    private function getSuperAdminData(): array
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();

        $todayPresent = Attendance::whereDate('date', today())
            ->where('status', '!=', 'absent')
            ->count();

        $pendingLeaves = Leave::where('status', 'pending')->count();
        $totalOrganizations = Organization::count();
        $totalBranches = Branch::count();

        $attendanceRate = $totalEmployees > 0 ? round(($todayPresent / $totalEmployees) * 100) : 0;

        $recentEmployees = Employee::with(['branch', 'department'])
            ->latest()
            ->limit(5)
            ->get();

        $recentActivity = AuditLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $attendanceTrend = $this->getAttendanceTrend(30);
        $leaveDistribution = $this->getLeaveDistribution();
        $headcountByDepartment = $this->getHeadcountByDepartment();
        $headcountByStatus = $this->getHeadcountByStatus();

        $lastMonthEmployees = Employee::where('created_at', '>=', now()->subMonth())->count();
        $prevMonthEmployees = Employee::where('created_at', '>=', now()->subMonths(2))
            ->where('created_at', '<', now()->subMonth())->count();
        $employeeTrend = $prevMonthEmployees > 0
            ? round((($lastMonthEmployees - $prevMonthEmployees) / $prevMonthEmployees) * 100)
            : ($lastMonthEmployees > 0 ? 100 : 0);

        $lastMonthLeaves = Leave::where('created_at', '>=', now()->subMonth())->count();
        $prevMonthLeaves = Leave::where('created_at', '>=', now()->subMonths(2))
            ->where('created_at', '<', now()->subMonth())->count();
        $leaveTrend = $prevMonthLeaves > 0
            ? round((($lastMonthLeaves - $prevMonthLeaves) / $prevMonthLeaves) * 100)
            : ($lastMonthLeaves > 0 ? 100 : 0);

        return compact(
            'totalEmployees', 'activeEmployees', 'todayPresent', 'pendingLeaves',
            'totalOrganizations', 'totalBranches', 'attendanceRate',
            'recentEmployees', 'recentActivity', 'attendanceTrend',
            'leaveDistribution', 'headcountByDepartment', 'headcountByStatus',
            'employeeTrend', 'leaveTrend'
        );
    }

    private function getBranchAdminData(?int $branchId): array
    {
        $totalEmployees = Employee::where('branch_id', $branchId)->count();
        $activeEmployees = Employee::where('branch_id', $branchId)->where('status', 'active')->count();

        $todayPresent = Attendance::whereHas('employee', fn ($q) => $q->where('branch_id', $branchId))
            ->whereDate('date', today())
            ->where('status', '!=', 'absent')
            ->count();

        $pendingLeaves = Leave::whereHas('employee', fn ($q) => $q->where('branch_id', $branchId))
            ->where('status', 'pending')
            ->count();

        $attendanceRate = $totalEmployees > 0 ? round(($todayPresent / $totalEmployees) * 100) : 0;

        $recentEmployees = Employee::with(['branch', 'department'])
            ->where('branch_id', $branchId)
            ->latest()
            ->limit(5)
            ->get();

        $recentActivity = AuditLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $attendanceTrend = $this->getAttendanceTrend(30, $branchId);
        $leaveDistribution = $this->getLeaveDistribution($branchId);
        $headcountByDepartment = $this->getHeadcountByDepartment($branchId);
        $headcountByStatus = $this->getHeadcountByStatus($branchId);

        $totalBranches = 1;
        $totalOrganizations = 1;

        $lastMonthEmployees = Employee::where('branch_id', $branchId)
            ->where('created_at', '>=', now()->subMonth())->count();
        $prevMonthEmployees = Employee::where('branch_id', $branchId)
            ->where('created_at', '>=', now()->subMonths(2))
            ->where('created_at', '<', now()->subMonth())->count();
        $employeeTrend = $prevMonthEmployees > 0
            ? round((($lastMonthEmployees - $prevMonthEmployees) / $prevMonthEmployees) * 100)
            : ($lastMonthEmployees > 0 ? 100 : 0);

        $lastMonthLeaves = Leave::whereHas('employee', fn ($q) => $q->where('branch_id', $branchId))
            ->where('created_at', '>=', now()->subMonth())->count();
        $prevMonthLeaves = Leave::whereHas('employee', fn ($q) => $q->where('branch_id', $branchId))
            ->where('created_at', '>=', now()->subMonths(2))
            ->where('created_at', '<', now()->subMonth())->count();
        $leaveTrend = $prevMonthLeaves > 0
            ? round((($lastMonthLeaves - $prevMonthLeaves) / $prevMonthLeaves) * 100)
            : ($lastMonthLeaves > 0 ? 100 : 0);

        return compact(
            'totalEmployees', 'activeEmployees', 'todayPresent', 'pendingLeaves',
            'totalOrganizations', 'totalBranches', 'attendanceRate',
            'recentEmployees', 'recentActivity', 'attendanceTrend',
            'leaveDistribution', 'headcountByDepartment', 'headcountByStatus',
            'employeeTrend', 'leaveTrend'
        );
    }

    private function getAttendanceTrend(int $days = 30, ?int $branchId = null): array
    {
        $query = Attendance::where('date', '>=', now()->subDays($days));

        if ($branchId) {
            $query->whereHas('employee', fn ($q) => $q->where('branch_id', $branchId));
        }

        $data = $query->selectRaw('date, COUNT(*) as total, SUM(CASE WHEN status != \'absent\' THEN 1 ELSE 0 END) as present')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->map(fn ($d) => Carbon::parse($d)->format('M d'))->toArray(),
            'present' => $data->pluck('present')->toArray(),
            'total' => $data->pluck('total')->toArray(),
        ];
    }

    private function getLeaveDistribution(?int $branchId = null): array
    {
        $query = Leave::query();

        if ($branchId) {
            $query->whereHas('employee', fn ($q) => $q->where('branch_id', $branchId));
        }

        $data = $query->selectRaw('leave_type, COUNT(*) as count')
            ->groupBy('leave_type')
            ->pluck('count', 'leave_type')
            ->toArray();

        return [
            'labels' => array_map(fn ($t) => ucfirst($t), array_keys($data)),
            'data' => array_values($data),
        ];
    }

    private function getHeadcountByDepartment(?int $branchId = null): array
    {
        $query = Employee::where('employees.status', 'active')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->selectRaw('departments.name as dept_name, COUNT(*) as count');

        if ($branchId) {
            $query->where('employees.branch_id', $branchId);
        }

        $data = $query->groupBy('departments.name')
            ->pluck('count', 'dept_name')
            ->toArray();

        return [
            'labels' => array_keys($data),
            'data' => array_values($data),
        ];
    }

    private function getHeadcountByStatus(?int $branchId = null): array
    {
        $query = Employee::query();

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $data = $query->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'labels' => array_map(fn ($s) => ucfirst($s), array_keys($data)),
            'data' => array_values($data),
        ];
    }
}
