<?php

namespace App\Services;

use App\Models\WorkSchedule;
use App\Repositories\WorkScheduleRepository;

class WorkScheduleService
{
    public function __construct(private readonly WorkScheduleRepository $repo) {}

    public function create(array $data): WorkSchedule
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?WorkSchedule
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

    public function update(WorkSchedule $workSchedule, array $data): WorkSchedule
    {
        return $this->repo->update($workSchedule, $data);
    }

    public function delete(WorkSchedule $workSchedule): bool
    {
        return $this->repo->delete($workSchedule);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }
}
