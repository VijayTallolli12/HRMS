<?php

namespace App\Policies;

class EmployeePolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'employee';
    }
}
