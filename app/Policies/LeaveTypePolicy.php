<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class LeaveTypePolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'leave-type';
    }

    protected function getBranchId(Model $model): ?int
    {
        return null;
    }
}
