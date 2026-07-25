<?php

namespace App\Services\Payroll;

use App\Models\SalaryComponent;
use App\Repositories\SalaryComponentRepository;

class SalaryComponentService
{
    public function __construct(private readonly SalaryComponentRepository $repo) {}

    public function create(array $data): SalaryComponent
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?SalaryComponent
    {
        return $this->repo->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginate($perPage, $search);
    }

    public function update(SalaryComponent $salaryComponent, array $data): SalaryComponent
    {
        return $this->repo->update($salaryComponent, $data);
    }

    public function delete(SalaryComponent $salaryComponent): bool
    {
        return $this->repo->delete($salaryComponent);
    }
}
