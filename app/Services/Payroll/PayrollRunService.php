<?php

namespace App\Services\Payroll;

use App\Models\PayrollRun;
use App\Repositories\PayrollRunRepository;

class PayrollRunService
{
    public function __construct(private readonly PayrollRunRepository $repo) {}

    public function create(array $data): PayrollRun
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?PayrollRun
    {
        return $this->repo->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginate($perPage, $search);
    }

    public function update(PayrollRun $payrollRun, array $data): PayrollRun
    {
        return $this->repo->update($payrollRun, $data);
    }

    public function delete(PayrollRun $payrollRun): bool
    {
        return $this->repo->delete($payrollRun);
    }
}
