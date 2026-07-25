# ADR-004: V1 Two-Role Access Model & Employee Self Service Extension Point

## Status
Accepted

## Context
Version 1 of the HRMS defines a simplified access model with exactly two login roles:
1. **Super Admin** - unrestricted access to all modules
2. **Branch Admin** - manages all employee-related operations within their assigned branch

Employees are **records** in the HRMS only. They do **not** authenticate. There is no Employee login in Version 1.

## Decision

### Authentication Model
- Only Users authenticate (login with email/password)
- Users have roles: `super-admin` or `branch-admin`
- Users are scoped to a `tenant_id`, `organization_id`, and optionally `branch_id`
- Employees are standalone records with no `user_id` FK — completely decoupled from authentication

### Branch Admin Scope (V1)
Branch Admin has full operational control within their branch:

| Module | Access Level |
|--------|-------------|
| Employees | View, Create, Update |
| Attendance | View, Create, Update |
| Attendance Adjustments | View, Create, Update |
| Leave | View, Create, Update |
| Overtime Requests | View, Create, Update |
| Branch, Department, Designation, Shifts, etc. | View-only (reference data) |
| Tenants, Organizations | No access |

**No role gets `delete-*` permissions in V1.** Deletion is restricted to Super Admin via direct database operations or future admin tools.

### Navigation Visibility
- **Desktop nav**: Dashboard, Employees, Attendance, Leave (visible to all)
- **Super Admin-only nav**: Organizations, Branches
- **Role badge**: Shown in header next to user dropdown (purple = Super Admin, blue = Branch Admin)

### Employee Self Service (ESS) Extension Point
When ESS is added in a future version:
1. A nullable `employee_id` column on the `users` table links a User to their Employee record
2. A new `employee-self-service` permission module is added
3. A new `employee` role is created with ESS-specific permissions
4. The `Employee` model gains a `user()` relationship
5. The `BranchScopedPolicy` is NOT changed — ESS policies will extend it
6. The `ApplyBranchScope` trait is NOT changed — it already scopes by `branch_id`

**Key architectural invariant**: The existing two-role system, policies, and query scoping remain unchanged. ESS is additive only — new role, new permissions, new nullable FK. No refactoring required.

### Future Roles (V2+)
The architecture supports adding roles like `hr-manager`, `payroll-admin`, `department-head` without modifying existing roles. The `BranchScopedPolicy` abstract class and `ApplyBranchScope` trait are role-agnostic — they check the current user's role and scope accordingly.

## Consequences
- Simplified V1: 2 roles, 84 permissions, clear access boundaries
- No employee login complexity in V1
- ESS addition in V2 is a single migration + new role + new permissions — zero refactoring
- Branch-scoped data isolation is enforced at both query level (`ApplyBranchScope`) and policy level (`BranchScopedPolicy`)
