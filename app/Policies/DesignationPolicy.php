<?php

namespace App\Policies;

class DesignationPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'designation';
    }
}
