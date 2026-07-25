<?php

namespace App\Services;

use App\Models\CostCenter;
use App\Repositories\CostCenterRepository;

class CostCenterService
{
    public function __construct(private readonly CostCenterRepository $repo) {}

    public function create(array $data): CostCenter
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?CostCenter
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

    public function update(CostCenter $costCenter, array $data): CostCenter
    {
        return $this->repo->update($costCenter, $data);
    }

    public function delete(CostCenter $costCenter): bool
    {
        return $this->repo->delete($costCenter);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }
}
