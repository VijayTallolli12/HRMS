<?php

namespace App\Repositories;

use App\Models\PayrollRun;
use App\Traits\ApplyBranchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PayrollRunRepository
{
    use ApplyBranchScope;

    public function __construct(private readonly PayrollRun $model) {}

    public function create(array $data): PayrollRun
    {
        return $this->model->create($data);
    }

    public function find(int $id): ?PayrollRun
    {
        return $this->model->with(['organization', 'branch', 'processor', 'payslips.employee'])->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with(['organization', 'branch', 'processor']);

        $this->applyBranchScope($query);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function update(PayrollRun $payrollRun, array $data): PayrollRun
    {
        $payrollRun->update($data);

        return $payrollRun->fresh();
    }

    public function delete(PayrollRun $payrollRun): bool
    {
        return $payrollRun->delete();
    }
}
