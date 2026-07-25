<?php

namespace App\Services;

use App\Models\OvertimeRequest;
use App\Repositories\OvertimeRequestRepository;

class OvertimeRequestService
{
    public function __construct(private readonly OvertimeRequestRepository $repo) {}

    public function create(array $data): OvertimeRequest
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?OvertimeRequest
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

    public function update(OvertimeRequest $request, array $data): OvertimeRequest
    {
        return $this->repo->update($request, $data);
    }

    public function delete(OvertimeRequest $request): bool
    {
        return $this->repo->delete($request);
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
