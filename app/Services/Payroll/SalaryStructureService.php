<?php

namespace App\Services\Payroll;

use App\Models\SalaryStructure;
use App\Repositories\SalaryStructureRepository;

class SalaryStructureService
{
    public function __construct(private readonly SalaryStructureRepository $repo) {}

    public function create(array $data): SalaryStructure
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?SalaryStructure
    {
        return $this->repo->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginate($perPage, $search);
    }

    public function update(SalaryStructure $salaryStructure, array $data): SalaryStructure
    {
        return $this->repo->update($salaryStructure, $data);
    }

    public function delete(SalaryStructure $salaryStructure): bool
    {
        return $this->repo->delete($salaryStructure);
    }

    public function findByEmployee(int $employeeId): ?SalaryStructure
    {
        return $this->repo->findByEmployee($employeeId);
    }
}
