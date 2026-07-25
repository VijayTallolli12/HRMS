<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class OvertimeRequestPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'overtime-request';
    }

    protected function getBranchId(Model $model): ?int
    {
        return $model->employee->branch_id ?? null;
    }
}
