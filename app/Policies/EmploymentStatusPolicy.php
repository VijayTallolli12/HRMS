<?php

namespace App\Policies;

class EmploymentStatusPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'employment-status';
    }

    protected function hasBranch(): bool
    {
        return false;
    }
}
