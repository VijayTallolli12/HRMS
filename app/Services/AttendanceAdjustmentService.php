<?php

namespace App\Services;

use App\Models\AttendanceAdjustment;
use App\Repositories\AttendanceAdjustmentRepository;

class AttendanceAdjustmentService
{
    public function __construct(private readonly AttendanceAdjustmentRepository $repo) {}

    public function create(array $data): AttendanceAdjustment
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?AttendanceAdjustment
    {
        return $this->repo->find($id);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null, ?string $status = null)
    {
        return $this->repo->paginateByOrganization($organizationId, $perPage, $search, $status);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?string $status = null)
    {
        return $this->repo->paginate($perPage, $search, $status);
    }

    public function update(AttendanceAdjustment $adjustment, array $data): AttendanceAdjustment
    {
        return $this->repo->update($adjustment, $data);
    }

    public function delete(AttendanceAdjustment $adjustment): bool
    {
        return $this->repo->delete($adjustment);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }

    public function pendingCount(?int $organizationId = null): int
    {
        return $this->repo->pendingCount($organizationId);
    }
}
