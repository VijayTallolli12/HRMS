<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class AttendancePolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'attendance';
    }

    protected function getBranchId(Model $model): ?int
    {
        return $model->employee->branch_id ?? null;
    }
}
