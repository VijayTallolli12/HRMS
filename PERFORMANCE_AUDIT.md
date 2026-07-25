# Performance Audit Report

**Date:** 2026-07-25
**Scope:** Query performance, caching, N+1 issues

---

## Issues Fixed

### HIGH-01: Missing Database Indexes on High-Frequency Columns
- **Table:** `employees`
- **Columns:** `organization_id`, `branch_id`, `department_id`, `designation_id`
- **Impact:** Full table scans on the most-queried table (100K target scale)
- **Fix:** New migration adding all indexes

### HIGH-02: N+1 Eager Load in OvertimeRequestRepository
- **File:** `app/Repositories/OvertimeRequestRepository.php`
- **Issue:** `approver` relationship not eager-loaded but accessed in views
- **Fix:** Added `approver` to eager load list

### HIGH-03: Controller Full-Table Loads for Dropdowns
- **Files:** `AttendanceController`, `AttendanceAdjustmentController`
- **Issue:** `Attendance::orderBy('date', 'desc')->get()` loads entire attendance table
- **Fix:** Scoped dropdown queries with organization filter

### HIGH-04: Missing Model Relationships Causing Extra Queries
- **File:** `app/Models/Employee.php`
- **Issue:** No `attendances()`, `overtimeRequests()`, `attendanceAdjustments()` relationships
- **Fix:** Added all missing relationships

---

## Remaining Medium Issues

| # | Issue | Impact | Recommendation |
|---|-------|--------|----------------|
| M-01 | `LIKE '%term%'` on large tables | Full table scans on search | Add MySQL fulltext indexes |
| M-02 | Dropdown data fetched on every page load | 4-6 extra queries per request | Cache with TTL + invalidation |
| M-03 | `isSuperAdmin()`/`isBranchAdmin()` call Spatie `hasRole()` per request | DB hit per role check | Cache role names on user model |
| M-04 | Organization dropdown not scoped by tenant | Shows all orgs globally | Apply tenant scope in controllers |
| M-05 | Dashboard shows no aggregate data | Empty dashboard | Implement cached counts |
| M-06 | Dual scope application in OrganizationRepository | Redundant WHERE clause | Remove duplicate scope call |

---

## Cache Recommendations (Deferred)

| Data | TTL | Invalidation |
|------|-----|-------------|
| Organization list | 1 hour | On create/update/delete |
| Branch list by org | 1 hour | On create/update/delete |
| Department list by org | 1 hour | On create/update/delete |
| Designation list by dept | 1 hour | On create/update/delete |
| User roles | Session lifetime | On role change |
| Dashboard counts | 5 minutes | On relevant CRUD |

---

## Performance Baseline

| Metric | Current | Target |
|--------|---------|--------|
| Tests passing | 248 | 248+ |
| Test duration | ~183s | <120s |
| PHPStan errors | 0 | 0 |
| Pint violations | 0 | 0 |
| N+1 risks remaining | 4 (policy getBranchId) | 0 |
