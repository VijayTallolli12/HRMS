<?php

namespace App\Services;

use App\Models\EmployeeCategory;
use App\Repositories\EmployeeCategoryRepository;

class EmployeeCategoryService
{
    public function __construct(private readonly EmployeeCategoryRepository $repo) {}

    public function create(array $data): EmployeeCategory
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?EmployeeCategory
    {
        return $this->repo->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginate($perPage, $search);
    }

    public function update(EmployeeCategory $employeeCategory, array $data): EmployeeCategory
    {
        return $this->repo->update($employeeCategory, $data);
    }

    public function delete(EmployeeCategory $employeeCategory): bool
    {
        return $this->repo->delete($employeeCategory);
    }

    public function count(): int
    {
        return $this->repo->count();
    }
}
