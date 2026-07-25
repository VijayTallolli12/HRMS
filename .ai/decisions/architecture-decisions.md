# Architecture Decisions

## ADR-001: Shared Database Multi-Tenancy

**Status:** Accepted
**Date:** 2025-07-25

**Decision:** Use shared database with `tenant_id` column (not database-per-tenant or schema-per-tenant).

**Rationale:**
- Sufficient for target scale (100 companies, 1,000 branches, 100K employees)
- Simpler infrastructure and migrations
- Easier cross-tenant reporting
- Column-level scoping via query scopes

## ADR-002: Repository Pattern

**Status:** Accepted
**Date:** 2025-07-25

**Decision:** Implement Repository pattern for data access layer.

**Rationale:**
- Clean separation between business logic and data access
- Easier to test (mock repositories)
- Centralized search, pagination, scoping logic
- Consistent API across all modules

## ADR-003: Spatie Permission for RBAC

**Status:** Accepted
**Date:** 2025-07-25

**Decision:** Use spatie/laravel-permission for roles and permissions.

**Rationale:**
- Battle-tested package with large community
- Built-in Blade directives and middleware
- Database-backed (cacheable)
- Supports teams (tenants) via package features

## ADR-004: Audit Logging via Trait

**Status:** Accepted
**Date:** 2025-07-25

**Decision:** Implement audit logging via Eloquent trait (boot method) rather than package.

**Rationale:**
- Lightweight, no external dependency
- Captures created/updated/deleted events with old/new values
- Stores IP, user agent, URL for forensics
- Can be extended to queue-based logging later

## ADR-005: Blade Views over Livewire Full-Page

**Status:** Accepted
**Date:** 2025-07-25

**Decision:** Use traditional Blade views with Livewire components (not Livewire full-page components).

**Rationale:**
- Faster initial render (SSR)
- Simpler mental model
- Livewire can be added incrementally for interactive features
- Better SEO (full HTML on initial load)
