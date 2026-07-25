# DECISIONS

This file documents explicit assumptions and architectural choices made by the AI Engineering Team when source documentation in `.ai/` is incomplete or underspecified. The decisions below are intended to produce a scalable, secure, maintainable enterprise HRMS implementation aligned with the hinted stack (Laravel 12, PHP 8.4, MySQL 8, Livewire 3, Tailwind).

## 1. Tech Stack
- **Laravel**: target Laravel 12 and PHP 8.4 (per MASTER_PROMPT). Use latest LTS-friendly packages where possible.
- **Frontend**: Livewire 3 + Tailwind for admin UI, with a design-system-driven component library.
- **API/Auth**: Laravel Sanctum for SPA/mobile token authentication; OAuth2 (Passport) not required initially.
- **Permissions**: Use Spatie Permission for RBAC (roles, permissions, caching).

## 2. Multi-tenancy
- **Chosen approach**: Separate-database per tenant (database-per-tenant) as the default enterprise pattern for production. Rationale: stronger data isolation, easier per-tenant backups, and compliance suitability for enterprise customers.
- **Fallback option**: For early-stage or small customers, support single-database multi-tenant mode (tenant_id on all tenant-scoped tables). Implementation will be toggled via environment config (`TENANCY_MODE=database|row`).

## 3. Database strategy
- **Primary DB engine**: MySQL 8 (utf8mb4). Use strict modes and appropriate connection pooling.
- **Migrations & seeds**: Central migrations with tenant bootstrap scripts. For database-per-tenant, provision DB during tenant onboarding.
- **Schema design**: Core entities include `tenants`/`companies`, `branches`, `employees`, `users`, `roles`, `permissions`, `attendance`, `leaves`, `expenses`, `payroll_runs`, `audit_logs`, `notifications`, `settings`.

## 4. Security
- **OWASP guidance**: Follow OWASP Top 10 mitigations (sanitization, CSRF via Laravel middleware, XSS mitigation via escaped templates, SQL injection prevented by query builder/ORM use).
- **Authentication**: Passwords hashed with Argon2 or bcrypt; enforce strong password policies and rate-limiting on auth endpoints.
- **Transport & secrets**: TLS for network; environment secrets in platform vaults. DB credentials rotated periodically.

## 5. API & Mobile
- **API design**: Versioned, RESTful endpoints with consistent error envelope. Use per-tenant scoping and rate-limiting. API guidelines documented in `.ai/api/API_GUIDELINES.md` (placeholder) — follow those patterns.

## 6. CI / CD and Deployments
- **CI**: Run static analysis (PHPStan/Psalm), unit and feature tests, and container image builds on merge-to-main. Use pipeline stages to run tests per-tenant-mocks.
- **CD**: Blue/Green or rolling deployments; DB migrations run in safe mode with backward-compatible deployments.

## 7. Observability & Audit
- **Audit logs**: Central audit logging for security-sensitive events; store immutable logs per tenant and ship to centralized log store (ELK/Datadog).

## 8. Testing strategy
- **Automated tests**: Unit tests for domain logic, integration tests for services, end-to-end tests for critical user flows. Aim for testable services and decoupled business logic.

## 9. Decisions about missing docs
- Where `.ai` docs are placeholders, the team will follow the choices above (database-per-tenant, Sanctum, Spatie, Livewire, Tailwind). Any deviation that materially affects architecture will be logged here and raised to stakeholders.

## 10. Operational considerations
- **Backups**: Per-tenant backup policy with retention SLA. For database-per-tenant, snapshot DBs independently.
- **Scaling**: Stateless app servers behind load balancer; read replicas for heavy reporting workloads; worker queues for async jobs (Redis/Beanstalk/Queue driver).

---
Signed-off-by: AI Engineering Team
Date: 2026-07-25
