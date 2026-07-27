<?php

namespace App\Services;

use App\Models\Department;
use App\Repositories\DepartmentRepository;

class DepartmentService
{
    public function __construct(private readonly DepartmentRepository $repo) {}

    public function create(array $data): Department
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?Department
    {
        return $this->repo->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?array $filters = null)
    {
        return $this->repo->paginate($perPage, $search, $filters);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null, ?array $filters = null)
    {
        return $this->repo->paginateByOrganization($organizationId, $perPage, $search, $filters);
    }

    public function update(Department $department, array $data): Department
    {
        return $this->repo->update($department, $data);
    }

    public function delete(Department $department): bool
    {
        if ($department->employees()->exists()) {
            throw new \DomainException('Cannot delete a department while employees are assigned.');
        }

        return $this->repo->delete($department);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }
}
