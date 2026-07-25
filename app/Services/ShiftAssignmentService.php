<?php

namespace App\Services;

use App\Models\ShiftAssignment;
use App\Repositories\ShiftAssignmentRepository;

class ShiftAssignmentService
{
    public function __construct(private readonly ShiftAssignmentRepository $repo) {}

    public function create(array $data): ShiftAssignment
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?ShiftAssignment
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

    public function update(ShiftAssignment $shiftAssignment, array $data): ShiftAssignment
    {
        return $this->repo->update($shiftAssignment, $data);
    }

    public function delete(ShiftAssignment $shiftAssignment): bool
    {
        return $this->repo->delete($shiftAssignment);
    }

    public function count(): int
    {
        return $this->repo->count();
    }
}
