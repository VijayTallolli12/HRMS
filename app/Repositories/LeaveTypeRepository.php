<?php

namespace App\Repositories;

use App\Models\LeaveType;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeaveTypeRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly LeaveType $model) {}

    public function create(array $data): LeaveType
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?LeaveType
    {
        return $this->model->with('organization')->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with('organization');

        $this->applyBranchScope($query);

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

    public function update(LeaveType $leaveType, array $data): LeaveType
    {
        $leaveType->update($data);

        return $leaveType->fresh();
    }

    public function delete(LeaveType $leaveType): bool
    {
        return $leaveType->delete();
    }

    public function count(?int $organizationId = null): int
    {
        $query = $this->model->query();
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        $this->applyBranchScope($query);

        return $query->count();
    }
}
