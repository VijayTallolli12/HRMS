<?php

namespace App\Services;

use App\Models\EmploymentType;
use App\Repositories\EmploymentTypeRepository;

class EmploymentTypeService
{
    public function __construct(private readonly EmploymentTypeRepository $repo) {}

    public function create(array $data): EmploymentType
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?EmploymentType
    {
        return $this->repo->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginate($perPage, $search);
    }

    public function update(EmploymentType $employmentType, array $data): EmploymentType
    {
        return $this->repo->update($employmentType, $data);
    }

    public function delete(EmploymentType $employmentType): bool
    {
        return $this->repo->delete($employmentType);
    }

    public function count(): int
    {
        return $this->repo->count();
    }
}
