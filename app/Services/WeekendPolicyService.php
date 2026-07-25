<?php

namespace App\Services;

use App\Models\WeekendPolicy;
use App\Repositories\WeekendPolicyRepository;

class WeekendPolicyService
{
    public function __construct(private readonly WeekendPolicyRepository $repo) {}

    public function create(array $data): WeekendPolicy
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?WeekendPolicy
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

    public function update(WeekendPolicy $weekendPolicy, array $data): WeekendPolicy
    {
        return $this->repo->update($weekendPolicy, $data);
    }

    public function delete(WeekendPolicy $weekendPolicy): bool
    {
        return $this->repo->delete($weekendPolicy);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }
}
