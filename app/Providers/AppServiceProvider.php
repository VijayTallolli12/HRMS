<?php

namespace App\Providers;

use App\Http\Middleware\IdentifyTenant;
use App\Models\Attendance;
use App\Models\AttendanceAdjustment;
use App\Models\Branch;
use App\Models\CostCenter;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\EmploymentStatus;
use App\Models\EmploymentType;
use App\Models\Holiday;
use App\Models\LatePolicy;
use App\Models\Leave;
use App\Models\Organization;
use App\Models\OvertimeRequest;
use App\Models\ReportingHierarchy;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\Tenant;
use App\Models\WeekendPolicy;
use App\Models\WorkSchedule;
use App\Policies\AttendanceAdjustmentPolicy;
use App\Policies\AttendancePolicy;
use App\Policies\BranchPolicy;
use App\Policies\CostCenterPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\DesignationPolicy;
use App\Policies\EmployeeCategoryPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\EmploymentStatusPolicy;
use App\Policies\EmploymentTypePolicy;
use App\Policies\HolidayPolicy;
use App\Policies\LatePolicyPolicy;
use App\Policies\LeavePolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\OvertimeRequestPolicy;
use App\Policies\ReportingHierarchyPolicy;
use App\Policies\ShiftAssignmentPolicy;
use App\Policies\ShiftPolicy;
use App\Policies\TenantPolicy;
use App\Policies\WeekendPolicyPolicy;
use App\Policies\WorkSchedulePolicy;
use App\Services\BrandingService;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BrandingService::class);
    }

    public function boot(): void
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('identify.tenant', IdentifyTenant::class);
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        Gate::policy(Tenant::class, TenantPolicy::class);
        Gate::policy(Organization::class, OrganizationPolicy::class);
        Gate::policy(Branch::class, BranchPolicy::class);
        Gate::policy(Department::class, DepartmentPolicy::class);
        Gate::policy(Designation::class, DesignationPolicy::class);
        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(EmploymentType::class, EmploymentTypePolicy::class);
        Gate::policy(EmployeeCategory::class, EmployeeCategoryPolicy::class);
        Gate::policy(EmploymentStatus::class, EmploymentStatusPolicy::class);
        Gate::policy(Shift::class, ShiftPolicy::class);
        Gate::policy(ShiftAssignment::class, ShiftAssignmentPolicy::class);
        Gate::policy(WorkSchedule::class, WorkSchedulePolicy::class);
        Gate::policy(Holiday::class, HolidayPolicy::class);
        Gate::policy(WeekendPolicy::class, WeekendPolicyPolicy::class);
        Gate::policy(ReportingHierarchy::class, ReportingHierarchyPolicy::class);
        Gate::policy(CostCenter::class, CostCenterPolicy::class);
        Gate::policy(Attendance::class, AttendancePolicy::class);
        Gate::policy(AttendanceAdjustment::class, AttendanceAdjustmentPolicy::class);
        Gate::policy(OvertimeRequest::class, OvertimeRequestPolicy::class);
        Gate::policy(LatePolicy::class, LatePolicyPolicy::class);
        Gate::policy(Leave::class, LeavePolicy::class);

        View::composer('*', function ($view): void {
            $branding = app(BrandingService::class)->all();

            $view->with('branding', $branding);
            $view->with('appName', $branding['app_name']);
        });
    }
}
