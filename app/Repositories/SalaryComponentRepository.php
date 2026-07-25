<?php

namespace App\Repositories;

use App\Models\SalaryComponent;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SalaryComponentRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly SalaryComponent $model) {}

    public function create(array $data): SalaryComponent
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?SalaryComponent
    {
        return $this->model->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with('organization');

        $this->applyOrganizationScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(SalaryComponent $salaryComponent, array $data): SalaryComponent
    {
        $salaryComponent->update($data);

        return $salaryComponent->fresh();
    }

    public function delete(SalaryComponent $salaryComponent): bool
    {
        return $salaryComponent->delete();
    }
}
