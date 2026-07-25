<?php

namespace App\Repositories;

use App\Models\Tenant;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TenantRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly Tenant $model) {}

    public function create(array $data): Tenant
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?Tenant
    {
        return $this->model->with('users')->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->query();

        $this->applyTenantScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('domain', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(Tenant $tenant, array $data): Tenant
    {
        $tenant->update($data);

        return $tenant->fresh();
    }

    public function delete(Tenant $tenant): bool
    {
        return $tenant->delete();
    }

    public function forceDelete(Tenant $tenant): bool
    {
        return $tenant->forceDelete();
    }

    public function restore(Tenant $tenant): bool
    {
        return $tenant->restore();
    }

    public function count(): int
    {
        $query = $this->model->query();

        $this->applyTenantScope($query);

        return $query->count();
    }
}
