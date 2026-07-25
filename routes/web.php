<?php

use App\Http\Controllers\AttendanceAdjustmentController;
use App\Http\Controllers\AttendanceController;
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

    // User Management
    Route::resource('user-management', UserManagementController::class)->middleware('throttle:60,1');

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

    // Attendance imports & daily register
    Route::post('attendances/import', [AttendanceController::class, 'import'])->name('attendances.import');
    Route::get('attendances/daily-register', [AttendanceController::class, 'dailyRegister'])->name('attendances.daily-register');

    // Leave actions
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');

    // Leave Types & Balances
    Route::resource('leave-types', LeaveTypeController::class);
    Route::resource('leave-balances', LeaveBalanceController::class)->only(['index', 'show']);
});

require __DIR__.'/auth.php';
