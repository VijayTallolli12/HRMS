<?php

namespace App\Services;

use App\Models\LeaveBalance;
use App\Repositories\LeaveBalanceRepository;

class LeaveBalanceService
{
    public function __construct(private readonly LeaveBalanceRepository $repo) {}

    public function create(array $data): LeaveBalance
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?LeaveBalance
    {
        return $this->repo->find($id);
    }

    public function paginateByEmployee(int $employeeId, int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginateByEmployee($employeeId, $perPage, $search);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginate($perPage, $search);
    }

    public function update(LeaveBalance $leaveBalance, array $data): LeaveBalance
    {
        return $this->repo->update($leaveBalance, $data);
    }

    public function count(?int $employeeId = null): int
    {
        return $this->repo->count($employeeId);
    }
}
