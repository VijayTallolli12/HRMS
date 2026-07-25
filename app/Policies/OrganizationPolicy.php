<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class OrganizationPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'organization';
    }

    protected function hasBranch(): bool
    {
        return false;
    }

    protected function getTenantId(Model $model): ?int
    {
        return $model->tenant_id ?? null;
    }
}
