<?php

namespace App\Services;

use App\Models\EmploymentStatus;
use App\Repositories\EmploymentStatusRepository;

class EmploymentStatusService
{
    public function __construct(private readonly EmploymentStatusRepository $repo) {}

    public function create(array $data): EmploymentStatus
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?EmploymentStatus
    {
        return $this->repo->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginate($perPage, $search);
    }

    public function update(EmploymentStatus $employmentStatus, array $data): EmploymentStatus
    {
        return $this->repo->update($employmentStatus, $data);
    }

    public function delete(EmploymentStatus $employmentStatus): bool
    {
        return $this->repo->delete($employmentStatus);
    }

    public function count(): int
    {
        return $this->repo->count();
    }
}
