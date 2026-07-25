<?php

namespace App\Repositories;

use App\Models\AttendanceAdjustment;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AttendanceAdjustmentRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly AttendanceAdjustment $model) {}

    public function create(array $data): AttendanceAdjustment
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?AttendanceAdjustment
    {
        return $this->model->with(['organization', 'attendance', 'employee', 'requester', 'approver'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        $query = $this->model->with(['attendance', 'employee', 'requester']);

        $this->applyBranchScope($query);

        if ($search) {
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
        }
        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        $query = $this->model->where('organization_id', $organizationId)->with(['attendance', 'employee', 'requester']);

        $this->applyBranchScope($query);

        if ($search) {
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
        }
        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(AttendanceAdjustment $adjustment, array $data): AttendanceAdjustment
    {
        $adjustment->update($data);

        return $adjustment->fresh();
    }

    public function delete(AttendanceAdjustment $adjustment): bool
    {
        return $adjustment->delete();
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

    public function pendingCount(?int $organizationId = null): int
    {
        $query = $this->model->where('status', 'pending');
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        $this->applyBranchScope($query);

        return $query->count();
    }
}
