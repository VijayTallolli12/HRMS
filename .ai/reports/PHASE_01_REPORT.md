# Phase 01 Report — Tenant Onboarding & Core Auth

Date: 2026-07-25

Summary
- Implemented tenant onboarding scaffolding and linked users to tenants.
- Added migrations for `tenants` and `tenant_id` on `users`.
- Implemented a simple `IdentifyTenant` middleware (header or env based) and registered it.
- Added `Tenant` model and `TenantSeeder` to create a default tenant and admin user.
- Added Spatie permission-compatible migrations (roles, permissions, model_has_roles, model_has_permissions, role_has_permissions) so RBAC tests run successfully.

Verification
- Ran `php artisan migrate` — migrations applied successfully.
- Ran `php artisan db:seed --class=TenantSeeder` — default tenant and admin user created.
- Ran the full test suite (`vendor/bin/pest`) — all tests passed (26 tests).

Notes
- Middleware currently resolves tenant from `X-Tenant-ID` header or `APP_TENANT_ID` env. This is intentionally simple and will be extended to subdomain or domain mapping when multi-tenancy routing is required.
- Spatie package is installed; to maintain compatibility with vendor migrations we added equivalent migrations directly to the project to ensure test/CI stability. In future these can be replaced by published vendor migrations if desired.

Next steps
1. Harden tenant resolution (subdomain, domain mapping).
2. Implement tenant provisioning automation (DB-per-tenant scripts per `.ai/decisions/DECISIONS.md`) — this is planned for Phase 02.
3. Add policies and feature tests for tenant scoping and data isolation.
