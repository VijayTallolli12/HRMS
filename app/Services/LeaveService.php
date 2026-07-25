<?php

namespace App\Services;

use App\Models\Leave;
use App\Repositories\LeaveRepository;

class LeaveService
{
    public function __construct(private readonly LeaveRepository $repo) {}

    public function create(array $data): Leave
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?Leave
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

    public function update(Leave $leave, array $data): Leave
    {
        return $this->repo->update($leave, $data);
    }

    public function delete(Leave $leave): bool
    {
        return $this->repo->delete($leave);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }
}
