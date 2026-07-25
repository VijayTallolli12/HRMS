<?php

namespace App\Services;

use App\Models\Holiday;
use App\Repositories\HolidayRepository;

class HolidayService
{
    public function __construct(private readonly HolidayRepository $repo) {}

    public function create(array $data): Holiday
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?Holiday
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

    public function update(Holiday $holiday, array $data): Holiday
    {
        return $this->repo->update($holiday, $data);
    }

    public function delete(Holiday $holiday): bool
    {
        return $this->repo->delete($holiday);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }
}
