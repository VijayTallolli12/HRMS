# Changelog

All notable changes to this HRMS project will be documented in this file.

## [0.1.0] - 2025-07-25

### Foundation Hardening Sprint

#### Core Infrastructure
- Upgraded to Laravel 12 with PHP 8.2+ features
- Installed and configured PHPStan/Larastan (level 5), Rector (PHP 8.4), Laravel Pint
- Installed Debugbar and Telescope for development debugging
- Created AuditLog model with Auditable trait for model event tracking

#### Database
- Enhanced `users` table: tenant_id, status enum, soft deletes, indexes
- Enhanced `tenants` table: unique domain, unique name
- Enhanced `organizations` table: tenant FK, unique name/tax_id, created_by, status, soft deletes
- Enhanced `branches` table: unique org+name, status, created_by, soft deletes
- Created `departments` table: organization FK, unique org+name, status, soft deletes
- Created `designations` table: department FK, unique dept+title, level, status, soft deletes
- Created `employees` table: org/branch/dept/designation FKs, hire_date, status, meta, soft deletes
- Created `audit_logs` table: polymorphic, tenant/user scoping, event tracking
- All tables include: PK, FKs, indexes, unique constraints, soft deletes, audit fields

#### Models (8)
- User, Tenant, Organization, Branch, Department, Designation, Employee, AuditLog
- All with: relationships, casts, scopes, fillable, Auditable trait

#### Repositories (6)
- Tenant, Organization, Branch, Department, Designation, Employee
- Search, pagination, tenant scoping

#### Services (6)
- Tenant, Organization, Branch, Department, Designation, Employee

#### Form Requests (12)
- Store/Update pairs for all 6 modules
- Spatie permission checks via `hasAnyPermission()`

#### Policies (6)
- Tenant, Organization, Branch, Department, Designation, Employee
- Registered in AppServiceProvider via Gate::policy()

#### Controllers (6)
- TenantController, OrganizationController, BranchController, DepartmentController, DesignationController, EmployeeController
- Full CRUD with authorization

#### Events & Listeners
- OrganizationCreated, OrganizationUpdated, EmployeeCreated, EmployeeUpdated events
- SendWelcomeEmail, LogActivity listeners

#### Notifications
- OrganizationCreatedNotification, EmployeeCreatedNotification

#### Blade Views (29)
- 5 shared components: search-input, status-badge, empty-state, loading, delete-confirm
- 4 views per module: index, create, show, edit
- Responsive layout, empty states, success notifications, delete confirmation, permission checks

#### RBAC
- Spatie Permission: 24 permissions (6 modules x 4 actions), 5 roles (Super Admin, HR Manager, HR Staff, Branch Manager, Employee)
- RolePermissionSeeder for consistent permission setup

#### Seeders
- DatabaseSeeder, TenantSeeder, OrganizationSeeder, RolePermissionSeeder
- `php artisan migrate:fresh --seed` working

#### Testing
- 83 tests passing (135 assertions)
- 8 Unit tests (EmployeeTest, TenantTest, ExampleTest)
- 75 Feature tests (Auth: 10, Tenant: 9, Organization: 7, Branch: 7, Department: 6, Designation: 6, Employee: 10, Profile: 6)
- Test infrastructure: RefreshDatabaseAndRoles trait

#### Code Quality
- Laravel Pint: all files passing
- PHPStan/Larastan level 5: 0 errors
- Rector configured for PHP 8.4 modernizations
