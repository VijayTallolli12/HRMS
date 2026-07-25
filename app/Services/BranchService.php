<?php

namespace App\Services;

use App\Models\Branch;
use App\Repositories\BranchRepository;

class BranchService
{
    public function __construct(private readonly BranchRepository $repo) {}

    public function create(array $data): Branch
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?Branch
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

    public function update(Branch $branch, array $data): Branch
    {
        return $this->repo->update($branch, $data);
    }

    public function delete(Branch $branch): bool
    {
        return $this->repo->delete($branch);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }
}
