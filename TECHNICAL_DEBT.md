# Technical Debt Report

**Date:** 2026-07-25
**Scope:** Code quality, duplication, architectural issues

---

## Fixed This Audit

| # | Debt | Severity | Fix |
|---|------|----------|-----|
| 1 | Dead `BranchScope` service (100 lines) | Critical | Deleted |
| 2 | 4 repositories use inline auth instead of trait | Critical | Refactored to use `ApplyBranchScope` |
| 3 | Unscoped controller fallback queries | High | Added scope enforcement |

---

## Remaining Technical Debt

### CRITICAL (Deferred — Architecture Changes Required)

| # | Issue | Files | Effort |
|---|-------|-------|--------|
| 1 | **Service layer is pure pass-through** — 20 services with zero business logic (~860 lines) | All `app/Services/` | Large |
| 2 | **No repository/service interfaces** — can't swap implementations or mock | All repos/services | Large |
| 3 | **`auth()` called directly in repositories** — untestable outside HTTP context | `ApplyBranchScope` + 4 repos | Medium |

### HIGH

| # | Issue | Files | Effort |
|---|-------|-------|--------|
| 1 | **~1,300 lines of duplicate CRUD boilerplate** across repositories | All `app/Repositories/` | Medium |
| 2 | **Service pass-through pattern repeated 20 times** | All `app/Services/` | Medium |
| 3 | **No base repository** — shared `create/update/delete/count` repeated in every repo | All repos | Medium |
| 4 | **Business logic in repository** — `getMonthlySummary()` in `AttendanceRepository` | `AttendanceRepository.php` | Small |
| 5 | **No transaction management** for multi-table operations | Repos/Services | Medium |
| 6 | **Controllers use inconsistent route model binding** — some manual `is_numeric()` | 4 controllers | Small |
| 7 | **EmploymentType/Status/Category** repos apply org scope to tables without `organization_id` | 3 repos | Small |

### MEDIUM

| # | Issue | Files | Effort |
|---|-------|-------|--------|
| 1 | Search `where` callback pattern duplicated 15+ times | All repos | Small |
| 2 | `count()` method signatures inconsistent across repos | All repos | Small |
| 3 | `paginate()` parameter lists differ across repos | All repos | Small |
| 4 | `find()` eager loading differs per repo | All repos | Small |
| 5 | No unit tests for Service/Repository layer | `tests/` | Medium |
| 6 | No audit logging tests | `tests/` | Small |
| 7 | Dashboard is a stub with no content | `dashboard.blade.php` | Medium |

### LOW

| # | Issue | Files | Effort |
|---|-------|-------|--------|
| 1 | `Tenant::scopeActive` manually checks `deleted_at` | `Tenant.php` | Trivial |
| 2 | No `created_by` on `late_policies` | Migration | Trivial |
| 3 | Nullable `unique(tax_id)` differs across DBs | Migration | Trivial |
| 4 | `OrganizationRepository::paginate()` has redundant `$tenantId` parameter | `OrganizationRepository.php` | Trivial |

---

## Code Metrics

| Metric | Value |
|--------|-------|
| Total PHP files | ~150 |
| Models | 22 |
| Controllers | 20 |
| Repositories | 20 |
| Services | 20 |
| Policies | 22 |
| Form Requests | 40 |
| Blade Views | 85+ |
| Migrations | 30+ |
| Tests | 248 |
| Estimated duplicate code | ~1,300 lines |
| Estimated dead code | ~100 lines (BranchScope - fixed) |
