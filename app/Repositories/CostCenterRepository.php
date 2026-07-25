<?php

namespace App\Repositories;

use App\Models\CostCenter;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CostCenterRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly CostCenter $model) {}

    public function create(array $data): CostCenter
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?CostCenter
    {
        return $this->model->with(['organization', 'department'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with(['organization', 'department']);

        $this->applyOrganizationScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with('department')->where('organization_id', $organizationId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(CostCenter $costCenter, array $data): CostCenter
    {
        $costCenter->update($data);

        return $costCenter->fresh();
    }

    public function delete(CostCenter $costCenter): bool
    {
        return $costCenter->delete();
    }

    public function count(?int $organizationId = null): int
    {
        $query = $this->model->query();
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        $this->applyOrganizationScope($query);

        return $query->count();
    }
}
