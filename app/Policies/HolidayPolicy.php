<?php

namespace App\Policies;

class HolidayPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'holiday';
    }

    protected function hasBranch(): bool
    {
        return false;
    }
}
