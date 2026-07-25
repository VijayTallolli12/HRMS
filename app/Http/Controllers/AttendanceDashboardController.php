<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceImportBatch;
use App\Models\Employee;
use App\Models\MissingPunch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $branchId = $user->branch_id;

        $today = now()->toDateString();

        $employeeQuery = Employee::where('status', 'active');
        if (! $isSuperAdmin && $branchId) {
            $employeeQuery->where('branch_id', $branchId);
        }
        $totalEmployees = $employeeQuery->count();

        $todayQuery = Attendance::whereDate('date', $today);
        if (! $isSuperAdmin && $branchId) {
            $todayQuery->whereHas('employee', fn ($q) => $q->where('branch_id', $branchId));
        }

        $presentToday = (clone $todayQuery)->where('status', '!=', 'absent')->count();
        $lateToday = (clone $todayQuery)->where('status', 'late')->count();
        $absentToday = max(0, $totalEmployees - $presentToday);

        $attendanceRate = $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0;

        $missingPunchesToday = MissingPunch::where('date', $today)
            ->where('status', 'pending')
            ->when(! $isSuperAdmin && $branchId, fn ($q) => $q->whereHas('employee', fn ($eq) => $eq->where('branch_id', $branchId)))
            ->count();

        $recentImports = AttendanceImportBatch::with(['importer', 'branch'])
            ->when(! $isSuperAdmin && $branchId, fn ($q) => $q->where('branch_id', $branchId))
            ->latest()
            ->limit(5)
            ->get();

        $attendanceTrend = $this->getAttendanceTrend($isSuperAdmin ? null : $branchId);

        $todayEmployees = Attendance::whereDate('date', $today)
            ->with(['employee'])
            ->when(! $isSuperAdmin && $branchId, fn ($q) => $q->whereHas('employee', fn ($eq) => $eq->where('branch_id', $branchId)))
            ->get();

        return view('attendances.dashboard', compact(
            'totalEmployees', 'presentToday', 'lateToday', 'absentToday',
            'attendanceRate', 'missingPunchesToday', 'recentImports',
            'attendanceTrend', 'todayEmployees', 'isSuperAdmin'
        ));
    }

    private function getAttendanceTrend(?int $branchId): array
    {
        $data = Attendance::where('date', '>=', now()->subDays(30))
            ->when($branchId, fn ($q) => $q->whereHas('employee', fn ($eq) => $eq->where('branch_id', $branchId)))
            ->selectRaw('date, COUNT(*) as total, SUM(CASE WHEN status != \'absent\' THEN 1 ELSE 0 END) as present')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->map(fn ($d) => Carbon::parse($d)->format('M d'))->toArray(),
            'present' => $data->pluck('present')->toArray(),
            'total' => $data->pluck('total')->toArray(),
        ];
    }
}
