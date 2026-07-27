<?php

namespace App\Services;

use App\Models\Designation;
use App\Repositories\DesignationRepository;

class DesignationService
{
    public function __construct(private readonly DesignationRepository $repo) {}

    public function create(array $data): Designation
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?Designation
    {
        return $this->repo->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?array $filters = null)
    {
        return $this->repo->paginate($perPage, $search, $filters);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null, ?array $filters = null)
    {
        return $this->repo->paginateByOrganization($organizationId, $perPage, $search, $filters);
    }

    public function update(Designation $designation, array $data): Designation
    {
        return $this->repo->update($designation, $data);
    }

    public function delete(Designation $designation): bool
    {
        if ($designation->employees()->exists()) {
            throw new \DomainException('Cannot delete a designation while employees are assigned.');
        }

        return $this->repo->delete($designation);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }
}
