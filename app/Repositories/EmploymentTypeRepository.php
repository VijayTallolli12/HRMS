<?php

namespace App\Repositories;

use App\Models\EmploymentType;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmploymentTypeRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly EmploymentType $model) {}

    public function create(array $data): EmploymentType
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?EmploymentType
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

    public function update(EmploymentType $employmentType, array $data): EmploymentType
    {
        $employmentType->update($data);

        return $employmentType->fresh();
    }

    public function delete(EmploymentType $employmentType): bool
    {
        return $employmentType->delete();
    }

    public function count(): int
    {
        $query = $this->model->query();

        $this->applyOrganizationScope($query);

        return $query->count();
    }
}
