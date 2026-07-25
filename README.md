# HRMS - Human Resource Management System

A multi-tenant HRMS built with Laravel 12, Livewire 3, and Spatie Permission.

## Tech Stack

- **Framework:** Laravel 12
- **PHP:** 8.2+
- **Livewire:** 3.8
- **RBAC:** Spatie Laravel-Permission 8.3
- **Testing:** Pest
- **Code Quality:** PHPStan/Larastan (level 5), Laravel Pint, Rector

## Modules

| Module | Status | Permissions |
|--------|--------|------------|
| Tenant | Complete | view, create, update, delete |
| Organization | Complete | view, create, update, delete |
| Branch | Complete | view, create, update, delete |
| Department | Complete | view, create, update, delete |
| Designation | Complete | view, create, update, delete |
| Employee | Complete | view, create, update, delete |

## Architecture

```
app/
  Http/
    Controllers/    # Resource controllers with authorization
    Middleware/      # IdentifyTenant
    Requests/        # Form request validation (12 classes)
  Models/           # 8 Eloquent models with relationships
  Repositories/     # 6 repository classes (search, pagination, scoping)
  Services/         # 6 service classes (business logic)
  Policies/         # 6 policy classes (authorization)
  Events/           # 4 domain events
  Listeners/        # 2 event listeners
  Notifications/    # 2 notifications
  Traits/           # Auditable trait
database/
  migrations/       # 16 migrations (enhanced with FKs, indexes, constraints)
  factories/        # 7 model factories
  seeders/          # 4 seeders (roles, tenants, organizations)
resources/views/
  components/       # 19 Blade components (5 shared + 14 original)
  {module}/         # 4 views per module (index, create, show, edit)
tests/
  Unit/             # 3 test files
  Feature/          # 10 test files
```

## Setup

```bash
# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate:fresh --seed

# Run tests
php artisan test

# Code quality
vendor\bin\pint
vendor\bin\phpstan analyse
```

## Roles & Permissions

| Role | Permissions |
|------|------------|
| Super Admin | All permissions |
| HR Manager | Full org/branch/dept/designation/employee CRUD |
| HR Staff | View all, limited create/update |
| Branch Manager | Branch-scoped employee management |
| Employee | View own profile only |

## Testing

```bash
php artisan test                    # Run all tests
vendor\bin\pest                     # Run via Pest
vendor\bin\phpstan analyse          # Static analysis
vendor\bin\pint                     # Code style fix
```
