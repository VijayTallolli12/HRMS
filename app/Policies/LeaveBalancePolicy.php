<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class LeaveBalancePolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'leave-balance';
    }

    protected function getBranchId(Model $model): ?int
    {
        return $model->employee->branch_id ?? null;
    }
}
