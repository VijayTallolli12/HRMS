<?php

namespace App\Policies;

class WeekendPolicyPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'weekend-policy';
    }

    protected function hasBranch(): bool
    {
        return false;
    }
}
