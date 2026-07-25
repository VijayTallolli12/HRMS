<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class AttendanceAdjustmentPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'attendance-adjustment';
    }

    protected function getBranchId(Model $model): ?int
    {
        return $model->employee->branch_id ?? null;
    }
}
