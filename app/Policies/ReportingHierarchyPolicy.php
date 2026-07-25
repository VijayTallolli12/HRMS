<?php

namespace App\Policies;

class ReportingHierarchyPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'reporting-hierarchy';
    }

    protected function hasBranch(): bool
    {
        return false;
    }
}
