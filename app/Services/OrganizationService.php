<?php

namespace App\Services;

use App\Models\Organization;
use App\Repositories\OrganizationRepository;

class OrganizationService
{
    public function __construct(private readonly OrganizationRepository $repo) {}

    public function create(array $data): Organization
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?Organization
    {
        return $this->repo->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?int $tenantId = null)
    {
        return $this->repo->paginate($perPage, $search, $tenantId);
    }

    public function update(Organization $organization, array $data): Organization
    {
        return $this->repo->update($organization, $data);
    }

    public function delete(Organization $organization): bool
    {
        return $this->repo->delete($organization);
    }

    public function count(?int $tenantId = null): int
    {
        return $this->repo->count($tenantId);
    }
}
