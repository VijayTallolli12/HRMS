<?php

namespace App\Services;

use App\Models\Attendance;
use App\Repositories\AttendanceRepository;

class AttendanceService
{
    public function __construct(private readonly AttendanceRepository $repo) {}

    public function create(array $data): Attendance
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?Attendance
    {
        return $this->repo->find($id);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $status = null)
    {
        return $this->repo->paginateByOrganization($organizationId, $perPage, $search, $dateFrom, $dateTo, $status);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $status = null)
    {
        return $this->repo->paginate($perPage, $search, $dateFrom, $dateTo, $status);
    }

    public function update(Attendance $attendance, array $data): Attendance
    {
        return $this->repo->update($attendance, $data);
    }

    public function delete(Attendance $attendance): bool
    {
        return $this->repo->delete($attendance);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }

    public function findForEmployeeAndDate(int $employeeId, string $date): ?Attendance
    {
        return $this->repo->findForEmployeeAndDate($employeeId, $date);
    }

    public function getMonthlySummary(int $employeeId, int $year, int $month): array
    {
        return $this->repo->getMonthlySummary($employeeId, $year, $month);
    }
}
