<?php

use App\Http\Controllers\AttendanceAdjustmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceCorrectionController;
use App\Http\Controllers\AttendanceDashboardController;
use App\Http\Controllers\AttendanceImportController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeCategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmploymentStatusController;
use App\Http\Controllers\EmploymentTypeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LatePolicyController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\MissingPunchController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OvertimeRequestController;
use App\Http\Controllers\Payroll\PayrollRunController;
use App\Http\Controllers\Payroll\SalaryComponentController;
use App\Http\Controllers\Payroll\SalaryStructureController;
use App\Http\Controllers\ReportingHierarchyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShiftAssignmentController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\WeekendPolicyController;
use App\Http\Controllers\WorkScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['identify.tenant'])->group(function () {
    Route::get('/', fn () => redirect()->route('login'))->name('home');

    Route::get('dashboard', DashboardController::class)
        ->middleware(['auth'])
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::resource('tenants', TenantController::class)->middleware('throttle:60,1');
    Route::resource('organizations', OrganizationController::class)->middleware('throttle:60,1');
    Route::resource('branches', BranchController::class)->middleware('throttle:60,1');
    Route::resource('departments', DepartmentController::class)->middleware('throttle:60,1');
    Route::resource('designations', DesignationController::class)->middleware('throttle:60,1');
    Route::resource('employees', EmployeeController::class)->middleware('throttle:60,1');
    Route::resource('employment-types', EmploymentTypeController::class)->middleware('throttle:60,1');
    Route::resource('employee-categories', EmployeeCategoryController::class)->middleware('throttle:60,1');
    Route::resource('employment-statuses', EmploymentStatusController::class)->middleware('throttle:60,1');
    Route::resource('shifts', ShiftController::class)->middleware('throttle:60,1');
    Route::resource('shift-assignments', ShiftAssignmentController::class)->middleware('throttle:60,1');
    Route::resource('work-schedules', WorkScheduleController::class)->middleware('throttle:60,1');
    Route::resource('holidays', HolidayController::class)->middleware('throttle:60,1');
    Route::resource('weekend-policies', WeekendPolicyController::class)->middleware('throttle:60,1');
    Route::resource('reporting-hierarchies', ReportingHierarchyController::class)->middleware('throttle:60,1');
    Route::resource('cost-centers', CostCenterController::class)->middleware('throttle:60,1');
    Route::resource('attendances', AttendanceController::class)->middleware('throttle:60,1');
    Route::resource('attendance-adjustments', AttendanceAdjustmentController::class)->middleware('throttle:60,1');
    Route::resource('overtime-requests', OvertimeRequestController::class)->middleware('throttle:60,1');
    Route::resource('late-policies', LatePolicyController::class)->middleware('throttle:60,1');
    Route::resource('leaves', LeaveController::class)->middleware('throttle:60,1');

    // Attendance Module
    Route::get('attendance/dashboard', AttendanceDashboardController::class)
        ->name('attendances.dashboard');
    Route::get('attendance/daily-register', [AttendanceController::class, 'dailyRegister'])
        ->name('attendances.daily-register');
    Route::get('attendance/corrections', [AttendanceCorrectionController::class, 'index'])
        ->name('attendances.corrections.index');
    Route::get('attendance/corrections/{attendance}/edit', [AttendanceCorrectionController::class, 'edit'])
        ->name('attendances.corrections.edit');
    Route::put('attendance/corrections/{attendance}', [AttendanceCorrectionController::class, 'update'])
        ->name('attendances.corrections.update');

    // Attendance Import
    Route::get('attendance/import', [AttendanceImportController::class, 'create'])
        ->name('attendances.import.create');
    Route::post('attendance/import', [AttendanceImportController::class, 'store'])
        ->name('attendances.import.store');
    Route::get('attendance/import/history', [AttendanceImportController::class, 'index'])
        ->name('attendances.import.history');
    Route::get('attendance/import/{batch}/preview', [AttendanceImportController::class, 'preview'])
        ->name('attendances.import.preview');
    Route::post('attendance/import/{batch}/commit', [AttendanceImportController::class, 'commit'])
        ->name('attendances.import.commit');
    Route::get('attendance/import/{batch}/results', [AttendanceImportController::class, 'results'])
        ->name('attendances.import.results');
    Route::get('attendance/import/template', [AttendanceImportController::class, 'template'])
        ->name('attendances.import.template');
    Route::get('attendance/import/{batch}/download-invalid', [AttendanceImportController::class, 'downloadInvalid'])
        ->name('attendances.import.download-invalid');

    // Missing Punches
    Route::get('missing-punches', [MissingPunchController::class, 'index'])
        ->name('missing-punches.index');
    Route::get('missing-punches/{missingPunch}', [MissingPunchController::class, 'show'])
        ->name('missing-punches.show');
    Route::post('missing-punches', [MissingPunchController::class, 'store'])
        ->name('missing-punches.store');
    Route::post('missing-punches/{missingPunch}/resolve', [MissingPunchController::class, 'resolve'])
        ->name('missing-punches.resolve');
    Route::post('missing-punches/{missingPunch}/dismiss', [MissingPunchController::class, 'dismiss'])
        ->name('missing-punches.dismiss');

    // Attendance Reports
    Route::get('attendance/reports', [AttendanceReportController::class, 'index'])
        ->name('attendances.reports');
    Route::get('attendance/reports/monthly', [AttendanceReportController::class, 'monthly'])
        ->name('attendances.reports.monthly');

    // User Management
    Route::resource('user-management', UserManagementController::class)->middleware('throttle:60,1');
    Route::post('user-management/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('user-management.toggle-status');
    Route::post('user-management/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('user-management.reset-password');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Payroll
    Route::prefix('payroll')->name('payroll.')->group(function () {
        Route::resource('runs', PayrollRunController::class);
        Route::resource('salary-components', SalaryComponentController::class);
        Route::resource('salary-structures', SalaryStructureController::class);
        Route::get('payslips/{payslip}', [PayrollRunController::class, 'showPayslip'])->name('payslips.show');
    });

    // Employee imports
    Route::post('employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::get('employees/export', [EmployeeController::class, 'export'])->name('employees.export');
    Route::get('employees/import/template', [EmployeeController::class, 'downloadTemplate'])->name('employees.import.template');
    Route::patch('employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');

    // Leave actions
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');

    // Leave Types & Balances
    Route::resource('leave-types', LeaveTypeController::class);
    Route::resource('leave-balances', LeaveBalanceController::class)->only(['index', 'show']);
});

require __DIR__.'/auth.php';
