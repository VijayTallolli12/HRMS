<?php

namespace App\Policies;

class EmployeeCategoryPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'employee-category';
    }

    protected function hasBranch(): bool
    {
        return false;
    }
}
