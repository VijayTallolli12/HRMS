<?php

namespace App\Repositories;

use App\Models\Leave;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeaveRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly Leave $model) {}

    public function create(array $data): Leave
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?Leave
    {
        return $this->model->with(['organization', 'employee', 'approver', 'creator'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        $query = $this->model->with(['organization', 'employee']);

        $this->applyBranchScope($query);

        if ($search) {
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
        }
        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest('start_date')->paginate($perPage);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        $query = $this->model->where('organization_id', $organizationId)->with(['employee']);

        $this->applyBranchScope($query);

        if ($search) {
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
        }
        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest('start_date')->paginate($perPage);
    }

    public function update(Leave $leave, array $data): Leave
    {
        $leave->update($data);

        return $leave->fresh();
    }

    public function delete(Leave $leave): bool
    {
        return $leave->delete();
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
