<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TenantPolicy extends BranchScopedPolicy
{
    protected function permissionPrefix(): string
    {
        return 'tenant';
    }

    protected function hasBranch(): bool
    {
        return false;
    }

    protected function canAccessModel(User $user, Model $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $model->getKey() === $user->tenant_id;
    }
}
