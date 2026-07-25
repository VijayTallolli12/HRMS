<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class ShiftAssignmentPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'shift-assignment';
    }

    protected function getBranchId(Model $model): ?int
    {
        return $model->employee->branch_id ?? null;
    }
}
