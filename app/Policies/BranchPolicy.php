<?php

namespace App\Policies;

class BranchPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'branch';
    }

    protected function hasBranch(): bool
    {
        return false;
    }
}
