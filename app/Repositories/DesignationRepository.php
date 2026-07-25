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
        return $this->model->with(['department', 'organization'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with(['department', 'organization']);

        $this->applyOrganizationScope($query);

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function paginateByDepartment(int $departmentId, int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->where('department_id', $departmentId);

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->where('organization_id', $organizationId);

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
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
