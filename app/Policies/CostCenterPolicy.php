<?php

namespace App\Policies;

class CostCenterPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'cost-center';
    }

    protected function hasBranch(): bool
    {
        return false;
    }
}
