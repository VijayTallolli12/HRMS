<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Services\AttendanceReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceReportController extends Controller
{
    public function __construct(
        private readonly AttendanceReportService $reportService,
    ) {}

    public function index(Request $request)
    {
        $branches = Branch::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        $branchId = $request->input('branch_id');
        $departmentId = $request->input('department_id');
        $reportType = $request->input('report_type', 'summary');

        $reportData = null;

        if ($request->has(['date_from', 'date_to'])) {
            $reportData = match ($reportType) {
                'late' => $this->reportService->getLateReport($dateFrom, $dateTo, $branchId),
                'default' => $this->reportService->getAttendanceReport($dateFrom, $dateTo, $branchId, $departmentId),
            };
        }

        return view('attendances.reports', compact(
            'branches', 'departments', 'reportData',
            'dateFrom', 'dateTo', 'branchId', 'departmentId', 'reportType'
        ));
    }

    public function monthly(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $branchId = $request->input('branch_id');
        $departmentId = $request->input('department_id');

        $data = $this->reportService->getMonthlySummary($year, $month, $branchId, $departmentId);
        $branches = Branch::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $monthName = Carbon::createFromDate($year, $month, 1)->format('F Y');
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        return view('attendances.monthly-register', compact(
            'data', 'branches', 'departments', 'year', 'month',
            'branchId', 'departmentId', 'monthName', 'daysInMonth'
        ));
    }
}
