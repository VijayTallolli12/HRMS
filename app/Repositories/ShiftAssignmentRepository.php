<?php

namespace App\Repositories;

use App\Models\ShiftAssignment;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ShiftAssignmentRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly ShiftAssignment $model) {}

    public function create(array $data): ShiftAssignment
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?ShiftAssignment
    {
        return $this->model->with(['employee', 'shift'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with(['employee', 'shift']);

        $this->applyBranchScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('notes', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function paginateByEmployee(int $employeeId, int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with(['employee', 'shift'])->where('employee_id', $employeeId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('notes', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(ShiftAssignment $shiftAssignment, array $data): ShiftAssignment
    {
        $shiftAssignment->update($data);

        return $shiftAssignment->fresh();
    }

    public function delete(ShiftAssignment $shiftAssignment): bool
    {
        return $shiftAssignment->delete();
    }

    public function count(): int
    {
        $query = $this->model->query();

        $this->applyBranchScope($query);

        return $query->count();
    }
}
