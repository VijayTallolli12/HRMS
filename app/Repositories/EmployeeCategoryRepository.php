<?php

namespace App\Repositories;

use App\Models\EmployeeCategory;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmployeeCategoryRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly EmployeeCategory $model) {}

    public function create(array $data): EmployeeCategory
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?EmployeeCategory
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
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('level', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(EmployeeCategory $employeeCategory, array $data): EmployeeCategory
    {
        $employeeCategory->update($data);

        return $employeeCategory->fresh();
    }

    public function delete(EmployeeCategory $employeeCategory): bool
    {
        return $employeeCategory->delete();
    }

    public function count(): int
    {
        $query = $this->model->query();

        $this->applyOrganizationScope($query);

        return $query->count();
    }
}
