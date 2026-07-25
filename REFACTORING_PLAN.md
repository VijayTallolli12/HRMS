# Refactoring Plan

**Date:** 2026-07-25
**Scope:** Prioritized refactoring roadmap

---

## Sprint 1: Immediate (Done)
- [x] Fix `BranchScopedPolicy::canAccessModel()` security bypass
- [x] Fix cascade delete rules (users.tenant_id, requested_by)
- [x] Add missing database indexes
- [x] Delete dead `BranchScope` service
- [x] Fix inline auth checks in repositories
- [x] Add missing model relationships
- [x] Add rate limiting
- [x] Fix unscoped controller queries

## Sprint 2: Next Module (Leave/Payroll Prep)
- [ ] **Extract Base Repository** — Create `App\Repositories\BaseRepository` with shared `create/update/delete/count/find` + `ApplyBranchScope` trait integration. Refactor all 20 repos to extend it.
- [ ] **Add Service Business Logic** — Add validation, transaction management, event dispatching to services. Or remove the service layer entirely if no business logic is planned.
- [ ] **Implement Dashboard** — Replace stub with cached aggregate counts (total employees, pending approvals, attendance summary).
- [ ] **Add Dropdown Caching** — Cache organization/branch/department/designation lists with TTL and invalidation on CRUD.

## Sprint 3: Scale Prep
- [ ] **Database-Level Tenant Isolation** — Add global Eloquent scopes or `ScopedBy` attribute on all models.
- [ ] **Full-Text Search** — Replace `LIKE '%term%'` with MySQL fulltext indexes or search service.
- [ ] **Repository Interfaces** — Create contracts for dependency injection and testability.
- [ ] **Unit Test Coverage** — Add service/repository/policy unit tests.

## Sprint 4: Production Readiness
- [ ] **Audit Trail Tests** — Verify audit logging works correctly.
- [ ] **Rate Limiting Tuning** — Adjust throttle limits based on usage patterns.
- [ ] **Performance Monitoring** — Add query logging, slow query detection.
- [ ] **API Rate Limiting** — If API layer is added, implement token-based rate limiting.

---

## Refactoring Priority Matrix

| Priority | Refactoring | Impact | Effort |
|----------|------------|--------|--------|
| P1 | Extract base repository | High (eliminates ~500 lines duplication) | Medium |
| P1 | Remove or enrich service layer | High (clear architecture) | Medium |
| P2 | Implement dashboard | Medium (user experience) | Small |
| P2 | Cache dropdown data | Medium (performance) | Small |
| P3 | Database-level tenant isolation | High (security hardening) | Large |
| P3 | Full-text search | Medium (performance at scale) | Medium |
| P4 | Repository interfaces | Medium (testability) | Medium |
| P4 | Unit test coverage | Medium (reliability) | Large |
