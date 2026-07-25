# Database Review

**Date:** 2026-07-25
**Scope:** All 30+ migrations, schema design, constraints

---

## Issues Fixed

### CRITICAL-01: `users.tenant_id` Uses Cascade Delete
- **Migration:** `2026_07_25_000002_add_tenant_id_to_users_table.php`
- **Risk:** Deleting a tenant destroys ALL user accounts, sessions, roles, permissions
- **Fix:** New migration changing to `set null`

### CRITICAL-02: Missing Indexes on `employees` Table
- **Migration:** `2026_07_25_000006_create_employees_table.php`
- **Columns:** `organization_id`, `branch_id`, `department_id`, `designation_id`
- **Impact:** Full table scans on 100K+ row table
- **Fix:** New migration adding all indexes

### HIGH-01: `requested_by` Cascade Delete on Audit Columns
- **Files:** `attendance_adjustments`, `overtime_requests` tables
- **Risk:** Deleting a user erases their adjustment/overtime history
- **Fix:** New migration changing to `set null`

---

## Schema Health Summary

### Tables with Proper Design
| Table | PK | FK | Indexes | Unique | Soft Deletes | Audit |
|-------|----|----|---------|--------|-------------|-------|
| tenants | ✓ | — | ✓ | ✓ | ✓ | ✓ |
| organizations | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| branches | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| departments | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| designations | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| employees | ✓ | ✓ | ✓* | ✓ | ✓ | ✓ |
| attendances | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| shifts | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |

*After fix migration

### Cascade Rules After Fix

| Table | Column | Before | After |
|-------|--------|--------|-------|
| users | tenant_id | CASCADE | SET NULL |
| attendance_adjustments | requested_by | CASCADE | SET NULL |
| overtime_requests | requested_by | CASCADE | SET NULL |

### Remaining Index Recommendations

| Table | Column(s) | Priority |
|-------|-----------|----------|
| attendances | `(employee_id, date)` composite | Medium |
| attendances | `(status)` | Medium |
| attendance_adjustments | `(attendance_id, status)` composite | Low |
| overtime_requests | `(employee_id, status)` composite | Low |
| reporting_hierarchies | `(employee_id, is_active)` composite | Low |
