<?php

namespace App\Repositories;

use App\Models\ReportingHierarchy;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReportingHierarchyRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly ReportingHierarchy $model) {}

    public function create(array $data): ReportingHierarchy
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?ReportingHierarchy
    {
        return $this->model->with(['employee', 'manager'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with(['employee', 'manager']);

        $this->applyOrganizationScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('employee', function ($eq) use ($search) {
                    $eq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                })->orWhereHas('manager', function ($mq) use ($search) {
                    $mq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(ReportingHierarchy $reportingHierarchy, array $data): ReportingHierarchy
    {
        $reportingHierarchy->update($data);

        return $reportingHierarchy->fresh();
    }

    public function delete(ReportingHierarchy $reportingHierarchy): bool
    {
        return $reportingHierarchy->delete();
    }

    public function count(): int
    {
        $query = $this->model->query();

        $this->applyOrganizationScope($query);

        return $query->count();
    }
}
