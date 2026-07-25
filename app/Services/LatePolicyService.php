<?php

namespace App\Services;

use App\Models\LatePolicy;
use App\Repositories\LatePolicyRepository;

class LatePolicyService
{
    public function __construct(private readonly LatePolicyRepository $repo) {}

    public function create(array $data): LatePolicy
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?LatePolicy
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

    public function update(LatePolicy $policy, array $data): LatePolicy
    {
        return $this->repo->update($policy, $data);
    }

    public function delete(LatePolicy $policy): bool
    {
        return $this->repo->delete($policy);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }
}
