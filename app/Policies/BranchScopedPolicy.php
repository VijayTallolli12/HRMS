<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class BranchScopedPolicy
{
    abstract protected function permissionPrefix(): string;

    protected function hasBranch(): bool
    {
        return true;
    }

    protected function getBranchId(Model $model): ?int
    {
        return $model->branch_id ?? null;
    }

    protected function getOrganizationId(Model $model): ?int
    {
        return $model->organization_id ?? null;
    }

    protected function getTenantId(Model $model): ?int
    {
        if (method_exists($model, 'organization') && $model->relationLoaded('organization')) {
            $org = data_get($model, 'organization');

            return data_get($org, 'tenant_id');
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can("view-{$this->permissionPrefix()}");
    }

    public function view(User $user, Model $model): bool
    {
        if (! $user->can("view-{$this->permissionPrefix()}")) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $this->canAccessModel($user, $model);
    }

    public function create(User $user): bool
    {
        return $user->can("create-{$this->permissionPrefix()}");
    }

    public function update(User $user, Model $model): bool
    {
        if (! $user->can("update-{$this->permissionPrefix()}")) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $this->canAccessModel($user, $model);
    }

    public function delete(User $user, Model $model): bool
    {
        if (! $user->can("delete-{$this->permissionPrefix()}")) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $this->canAccessModel($user, $model);
    }

    protected function canAccessModel(User $user, Model $model): bool
    {
        if ($user->isBranchAdmin()) {
            if ($this->hasBranch()) {
                $modelBranchId = $this->getBranchId($model);
                if ($modelBranchId && $modelBranchId !== $user->branch_id) {
                    return false;
                }
            }

            $modelOrgId = $this->getOrganizationId($model);
            if ($modelOrgId && $modelOrgId !== $user->organization_id) {
                return false;
            }

            return true;
        }

        return false;
    }
}
