<?php

namespace App\Repositories;

use App\Models\WeekendPolicy;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WeekendPolicyRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly WeekendPolicy $model) {}

    public function create(array $data): WeekendPolicy
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?WeekendPolicy
    {
        return $this->model->with('organization')->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with('organization');

        $this->applyOrganizationScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->where('organization_id', $organizationId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(WeekendPolicy $weekendPolicy, array $data): WeekendPolicy
    {
        $weekendPolicy->update($data);

        return $weekendPolicy->fresh();
    }

    public function delete(WeekendPolicy $weekendPolicy): bool
    {
        return $weekendPolicy->delete();
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
