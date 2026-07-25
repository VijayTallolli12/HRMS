<?php

namespace App\Repositories;

use App\Models\Attendance;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class AttendanceRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly Attendance $model) {}

    public function create(array $data): Attendance
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?Attendance
    {
        return $this->model->with(['organization', 'employee', 'creator'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): LengthAwarePaginator
    {
        $query = $this->model->with(['organization', 'employee']);

        $this->applyBranchScope($query);

        if ($search) {
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
        }
        if ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('date', '<=', $dateTo);
        }
        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest('date')->paginate($perPage);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null, ?string $dateFrom = null, ?string $dateTo = null, ?string $status = null): LengthAwarePaginator
    {
        $query = $this->model->where('organization_id', $organizationId)->with(['employee']);

        $this->applyBranchScope($query);

        if ($search) {
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
        }
        if ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('date', '<=', $dateTo);
        }
        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest('date')->paginate($perPage);
    }

    public function update(Attendance $attendance, array $data): Attendance
    {
        $attendance->update($data);

        return $attendance->fresh();
    }

    public function delete(Attendance $attendance): bool
    {
        return $attendance->delete();
    }

    public function count(?int $organizationId = null): int
    {
        $query = $this->model->query();
        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        $this->applyBranchScope($query);

        return $query->count();
    }

    public function findForEmployeeAndDate(int $employeeId, string $date): ?Attendance
    {
        return $this->model->where('employee_id', $employeeId)->where('date', $date)->first();
    }

    public function getMonthlySummary(int $employeeId, int $year, int $month): array
    {
        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $records = $this->model->where('employee_id', $employeeId)
            ->whereBetween('date', [$start, $end])
            ->get();

        return [
            'total_days' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'late' => $records->where('status', 'late')->count(),
            'half_day' => $records->where('status', 'half-day')->count(),
            'total_hours' => $records->sum('hours_worked'),
            'total_overtime' => $records->sum('overtime_hours'),
            'total_late_minutes' => $records->sum('late_minutes'),
        ];
    }
}
