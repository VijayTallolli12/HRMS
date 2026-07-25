<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;

class EmployeeService
{
    public function __construct(private readonly EmployeeRepository $repo) {}

    public function create(array $data): Employee
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?Employee
    {
        return $this->repo->find($id);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null, ?array $filters = null)
    {
        return $this->repo->paginateByOrganization($organizationId, $perPage, $search, $filters);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginate($perPage, $search);
    }

    public function update(Employee $employee, array $data): Employee
    {
        return $this->repo->update($employee, $data);
    }

    public function delete(Employee $employee): bool
    {
        return $this->repo->delete($employee);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }
}
