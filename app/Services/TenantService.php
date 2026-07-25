<?php

namespace App\Services;

use App\Models\Tenant;
use App\Repositories\TenantRepository;

class TenantService
{
    public function __construct(private readonly TenantRepository $repo) {}

    public function create(array $data): Tenant
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?Tenant
    {
        return $this->repo->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginate($perPage, $search);
    }

    public function update(Tenant $tenant, array $data): Tenant
    {
        return $this->repo->update($tenant, $data);
    }

    public function delete(Tenant $tenant): bool
    {
        return $this->repo->delete($tenant);
    }

    public function count(): int
    {
        return $this->repo->count();
    }
}
