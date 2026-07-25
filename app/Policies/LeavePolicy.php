<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class LeavePolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'leave';
    }

    protected function getBranchId(Model $model): ?int
    {
        return $model->employee->branch_id ?? null;
    }
}
