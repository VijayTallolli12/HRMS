<?php

namespace App\Repositories;

use App\Models\Designation;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DesignationRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly Designation $model) {}

    public function create(array $data): Designation
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?Designation
    {
        return $this->model->with(['department', 'branch', 'organization'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?array $filters = null): LengthAwarePaginator
    {
        $query = $this->model->with(['department', 'branch', 'organization'])->withCount('employees');

        $this->applyOrganizationScope($query);
        $this->applyBranchScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('grade', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%");
            });
        }

        if ($filters) {
            if (! empty($filters['branch_id'])) {
                $query->where('branch_id', $filters['branch_id']);
            }
            if (! empty($filters['department_id'])) {
                $query->where('department_id', $filters['department_id']);
            }
            if (! empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function paginateByDepartment(int $departmentId, int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->where('department_id', $departmentId);

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null, ?array $filters = null): LengthAwarePaginator
    {
        $query = $this->model->with(['department', 'branch', 'organization'])
            ->withCount('employees')
            ->where('organization_id', $organizationId);

        $this->applyBranchScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('grade', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%");
            });
        }

        if ($filters) {
            if (! empty($filters['branch_id'])) {
                $query->where('branch_id', $filters['branch_id']);
            }
            if (! empty($filters['department_id'])) {
                $query->where('department_id', $filters['department_id']);
            }
            if (! empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function update(Designation $designation, array $data): Designation
    {
        $designation->update($data);

        return $designation->fresh();
    }

    public function delete(Designation $designation): bool
    {
        return $designation->delete();
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
