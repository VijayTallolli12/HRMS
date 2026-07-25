<?php

namespace App\Policies;

class WorkSchedulePolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'work-schedule';
    }

    protected function hasBranch(): bool
    {
        return false;
    }
}
