<?php

namespace App\Repositories;

use App\Models\OvertimeRequest;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OvertimeRequestRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly OvertimeRequest $model) {}

    public function create(array $data): OvertimeRequest
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?OvertimeRequest
    {
        return $this->model->with(['organization', 'employee', 'requester', 'approver'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        $query = $this->model->with(['employee', 'requester', 'approver']);

        if (! auth()->user()?->isSuperAdmin() && auth()->user()?->branch_id) {
            $query->whereHas('employee', fn ($q) => $q->where('branch_id', auth()->user()->branch_id));
        }

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
        $query = $this->model->where('organization_id', $organizationId)->with(['employee', 'requester', 'approver']);

        if (! auth()->user()?->isSuperAdmin() && auth()->user()?->branch_id) {
            $query->whereHas('employee', fn ($q) => $q->where('branch_id', auth()->user()->branch_id));
        }

        if ($search) {
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
        }
        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(OvertimeRequest $request, array $data): OvertimeRequest
    {
        $request->update($data);

        return $request->fresh();
    }

    public function delete(OvertimeRequest $request): bool
    {
        return $request->delete();
    }

    public function count(?int $organizationId = null): int
    {
        $query = $this->model->query();
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        if (! auth()->user()?->isSuperAdmin() && auth()->user()?->branch_id) {
            $query->whereHas('employee', fn ($q) => $q->where('branch_id', auth()->user()->branch_id));
        }

        return $query->count();
    }

    public function pendingCount(?int $organizationId = null): int
    {
        $query = $this->model->where('status', 'pending');
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        if (! auth()->user()?->isSuperAdmin() && auth()->user()?->branch_id) {
            $query->whereHas('employee', fn ($q) => $q->where('branch_id', auth()->user()->branch_id));
        }

        return $query->count();
    }
}
