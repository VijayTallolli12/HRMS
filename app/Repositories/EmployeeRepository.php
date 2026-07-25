<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmployeeRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly Employee $model) {}

    public function create(array $data): Employee
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?Employee
    {
        return $this->model->with(['organization', 'branch', 'department', 'designation', 'creator'])->find($id);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null, ?array $filters = null): LengthAwarePaginator
    {
        $query = $this->model->with(['branch', 'department', 'designation'])
            ->where('organization_id', $organizationId);

        $this->applyBranchScope($query, 'branch_id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($filters) {
            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (isset($filters['branch_id'])) {
                $query->where('branch_id', $filters['branch_id']);
            }
            if (isset($filters['department_id'])) {
                $query->where('department_id', $filters['department_id']);
            }
        }

        return $query->latest()->paginate($perPage);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with(['organization', 'branch', 'department', 'designation']);

        $this->applyBranchScope($query, 'branch_id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(Employee $employee, array $data): Employee
    {
        $employee->update($data);

        return $employee->fresh();
    }

    public function delete(Employee $employee): bool
    {
        return $employee->delete();
    }

    public function count(?int $organizationId = null): int
    {
        $query = $this->model->query();
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        $this->applyBranchScope($query, 'branch_id');

        return $query->count();
    }
}
