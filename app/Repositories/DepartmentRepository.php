<?php

namespace App\Repositories;

use App\Models\Department;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DepartmentRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly Department $model) {}

    public function create(array $data): Department
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?Department
    {
        return $this->model->with(['organization', 'branch', 'head', 'designations', 'employees'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?array $filters = null): LengthAwarePaginator
    {
        $query = $this->model->with(['organization', 'branch', 'head'])->withCount('employees');

        $this->applyOrganizationScope($query);
        $this->applyBranchScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($filters) {
            if (! empty($filters['branch_id'])) {
                $query->where('branch_id', $filters['branch_id']);
            }
            if (! empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null, ?array $filters = null): LengthAwarePaginator
    {
        $query = $this->model->with(['organization', 'branch', 'head'])
            ->withCount('employees')
            ->where('organization_id', $organizationId);

        $this->applyBranchScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($filters) {
            if (! empty($filters['branch_id'])) {
                $query->where('branch_id', $filters['branch_id']);
            }
            if (! empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function update(Department $department, array $data): Department
    {
        $department->update($data);

        return $department->fresh();
    }

    public function delete(Department $department): bool
    {
        return $department->delete();
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
