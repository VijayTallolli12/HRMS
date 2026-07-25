<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ApplyBranchScope
{
    protected function applyBranchScope(Builder $query, string $branchColumn = 'branch_id'): Builder
    {
        $user = auth()->user();

        if (! $user || $user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isBranchAdmin() && $user->branch_id) {
            return $query->where($branchColumn, $user->branch_id);
        }

        return $query->whereRaw('1 = 0');
    }

    protected function applyOrganizationScope(Builder $query, string $orgColumn = 'organization_id'): Builder
    {
        $user = auth()->user();

        if (! $user || $user->isSuperAdmin()) {
            return $query;
        }

        if ($user->organization_id) {
            return $query->where($orgColumn, $user->organization_id);
        }

        return $query->whereRaw('1 = 0');
    }

    protected function applyTenantScope(Builder $query, string $tenantColumn = 'tenant_id'): Builder
    {
        $user = auth()->user();

        if (! $user || $user->isSuperAdmin()) {
            return $query;
        }

        if ($user->tenant_id) {
            return $query->where($tenantColumn, $user->tenant_id);
        }

        return $query->whereRaw('1 = 0');
    }
}
