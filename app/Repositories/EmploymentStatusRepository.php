<?php

namespace App\Repositories;

use App\Models\EmploymentStatus;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmploymentStatusRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly EmploymentStatus $model) {}

    public function create(array $data): EmploymentStatus
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?EmploymentStatus
    {
        return $this->model->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->query();

        $this->applyOrganizationScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(EmploymentStatus $employmentStatus, array $data): EmploymentStatus
    {
        $employmentStatus->update($data);

        return $employmentStatus->fresh();
    }

    public function delete(EmploymentStatus $employmentStatus): bool
    {
        return $employmentStatus->delete();
    }

    public function count(): int
    {
        $query = $this->model->query();

        $this->applyOrganizationScope($query);

        return $query->count();
    }
}
