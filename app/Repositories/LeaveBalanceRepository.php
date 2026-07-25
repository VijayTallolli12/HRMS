<?php

namespace App\Repositories;

use App\Models\LeaveBalance;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeaveBalanceRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly LeaveBalance $model) {}

    public function create(array $data): LeaveBalance
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?LeaveBalance
    {
        return $this->model->with(['employee', 'leaveType'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with(['employee', 'leaveType']);

        $this->applyBranchScope($query);

        if ($search) {
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
        }

        return $query->latest()->paginate($perPage);
    }

    public function paginateByEmployee(int $employeeId, int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->where('employee_id', $employeeId)->with('leaveType');

        if ($search) {
            $query->whereHas('leaveType', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(LeaveBalance $leaveBalance, array $data): LeaveBalance
    {
        $leaveBalance->update($data);

        return $leaveBalance->fresh();
    }

    public function count(?int $employeeId = null): int
    {
        $query = $this->model->query();
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $this->applyBranchScope($query);

        return $query->count();
    }
}
