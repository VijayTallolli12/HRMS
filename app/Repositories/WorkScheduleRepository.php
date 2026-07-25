<?php

namespace App\Repositories;

use App\Models\WorkSchedule;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WorkScheduleRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly WorkSchedule $model) {}

    public function create(array $data): WorkSchedule
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?WorkSchedule
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

    public function update(WorkSchedule $workSchedule, array $data): WorkSchedule
    {
        $workSchedule->update($data);

        return $workSchedule->fresh();
    }

    public function delete(WorkSchedule $workSchedule): bool
    {
        return $workSchedule->delete();
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
