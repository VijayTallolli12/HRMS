<?php

namespace App\Services;

use App\Models\Shift;
use App\Repositories\ShiftRepository;

class ShiftService
{
    public function __construct(private readonly ShiftRepository $repo) {}

    public function create(array $data): Shift
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?Shift
    {
        return $this->repo->find($id);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginateByOrganization($organizationId, $perPage, $search);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginate($perPage, $search);
    }

    public function update(Shift $shift, array $data): Shift
    {
        return $this->repo->update($shift, $data);
    }

    public function delete(Shift $shift): bool
    {
        return $this->repo->delete($shift);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }
}
