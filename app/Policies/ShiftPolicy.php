<?php

namespace App\Policies;

class ShiftPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'shift';
    }

    protected function hasBranch(): bool
    {
        return false;
    }
}
