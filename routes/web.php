<?php

use App\Http\Controllers\AttendanceAdjustmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeCategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmploymentStatusController;
use App\Http\Controllers\EmploymentTypeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LatePolicyController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OvertimeRequestController;
use App\Http\Controllers\ReportingHierarchyController;
use App\Http\Controllers\ShiftAssignmentController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\WeekendPolicyController;
use App\Http\Controllers\WorkScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['identify.tenant'])->group(function () {
    Route::view('/', 'welcome');

    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');
});

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
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
});

require __DIR__.'/auth.php';
