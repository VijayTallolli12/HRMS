<?php

namespace App\Repositories;

use App\Models\SalaryStructure;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SalaryStructureRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly SalaryStructure $model) {}

    public function create(array $data): SalaryStructure
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?SalaryStructure
    {
        return $this->model->with(['employee', 'organization', 'components.salaryComponent'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with(['employee', 'organization']);

        $this->applyBranchScope($query);

        if ($search) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_number', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(SalaryStructure $salaryStructure, array $data): SalaryStructure
    {
        $salaryStructure->update($data);

        return $salaryStructure->fresh();
    }

    public function delete(SalaryStructure $salaryStructure): bool
    {
        return $salaryStructure->delete();
    }

    public function findByEmployee(int $employeeId): ?SalaryStructure
    {
        return $this->model->with(['components.salaryComponent'])
            ->where('employee_id', $employeeId)
            ->where('is_active', true)
            ->latest('effective_from')
            ->first();
    }
}
