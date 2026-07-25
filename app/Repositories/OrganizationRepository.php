<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrganizationRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly Organization $model) {}

    public function create(array $data): Organization
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?Organization
    {
        return $this->model->with(['branches', 'departments', 'employees'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?int $tenantId = null): LengthAwarePaginator
    {
        $query = $this->model->query();

        $this->applyTenantScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('legal_name', 'like', "%{$search}%")
                    ->orWhere('tax_id', 'like', "%{$search}%");
            });
        }

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(Organization $organization, array $data): Organization
    {
        $organization->update($data);

        return $organization->fresh();
    }

    public function delete(Organization $organization): bool
    {
        return $organization->delete();
    }

    public function count(?int $tenantId = null): int
    {
        $query = $this->model->query();
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        $this->applyTenantScope($query);

        return $query->count();
    }
}
