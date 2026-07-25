<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository
{
    public function create(array $data): Employee
    {
        return Employee::create($data);
    }

    public function find(int $id): ?Employee
    {
        return Employee::find($id);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15)
    {
        return Employee::where('organization_id', $organizationId)->paginate($perPage);
    }
}
