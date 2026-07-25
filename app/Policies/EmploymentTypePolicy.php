<?php

namespace App\Policies;

class EmploymentTypePolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'employment-type';
    }

    protected function hasBranch(): bool
    {
        return false;
    }
}
