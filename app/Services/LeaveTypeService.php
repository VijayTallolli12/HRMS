<?php

namespace App\Services;

use App\Models\LeaveType;
use App\Repositories\LeaveTypeRepository;

class LeaveTypeService
{
    public function __construct(private readonly LeaveTypeRepository $repo) {}

    public function create(array $data): LeaveType
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?LeaveType
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

    public function update(LeaveType $leaveType, array $data): LeaveType
    {
        return $this->repo->update($leaveType, $data);
    }

    public function delete(LeaveType $leaveType): bool
    {
        return $this->repo->delete($leaveType);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }
}
