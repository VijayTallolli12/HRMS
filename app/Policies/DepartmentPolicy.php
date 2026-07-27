<?php

namespace App\Policies;

class DepartmentPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'department';
    }
}
