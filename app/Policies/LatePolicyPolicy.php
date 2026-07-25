<?php

namespace App\Policies;

class LatePolicyPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'late-policy';
    }

    protected function hasBranch(): bool
    {
        return false;
    }
}
