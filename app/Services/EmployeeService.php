<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\ReportingHierarchy;
use App\Models\ShiftAssignment;
use App\Repositories\EmployeeRepository;

class EmployeeService
{
    public function __construct(private readonly EmployeeRepository $repo) {}

    public function create(array $data): Employee
    {
        $related = $this->extractRelatedWorkflowData($data);
        $data['employee_number'] = filled($data['employee_number'] ?? null) ? $data['employee_number'] : Employee::nextEmployeeNumber();
        $employee = $this->repo->create($data);

        $this->syncRelatedWorkflowData($employee, $related);

        return $employee;
    }

    public function find(int $id): ?Employee
    {
        return $this->repo->find($id);
    }

    public function paginateByOrganization(int $organizationId, int $perPage = 15, ?string $search = null, ?array $filters = null)
    {
        return $this->repo->paginateByOrganization($organizationId, $perPage, $search, $filters);
    }

    public function paginate(int $perPage = 15, ?string $search = null, ?array $filters = null)
    {
        return $this->repo->paginate($perPage, $search, $filters);
    }

    public function update(Employee $employee, array $data): Employee
    {
        $related = $this->extractRelatedWorkflowData($data);
        $employee = $this->repo->update($employee, $data);

        $this->syncRelatedWorkflowData($employee, $related);

        return $employee;
    }

    public function delete(Employee $employee): bool
    {
        return $this->repo->delete($employee);
    }

    public function count(?int $organizationId = null): int
    {
        return $this->repo->count($organizationId);
    }

    private function extractRelatedWorkflowData(array &$data): array
    {
        $related = [
            'reporting_manager_id' => $data['reporting_manager_id'] ?? null,
            'shift_id' => $data['shift_id'] ?? null,
        ];

        unset($data['reporting_manager_id'], $data['shift_id']);

        $metaFields = [
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'emergency_contact' => $data['emergency_contact'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'bank_account_number' => $data['bank_account_number'] ?? null,
            'ifsc_code' => $data['ifsc_code'] ?? null,
            'pan_number' => $data['pan_number'] ?? null,
            'uan_number' => $data['uan_number'] ?? null,
            'documents_note' => $data['documents_note'] ?? null,
            'attendance_mode' => $data['attendance_mode'] ?? null,
        ];

        if (array_intersect(array_keys($data), array_keys($metaFields))) {
            $data['meta'] = array_filter($metaFields, fn ($value) => filled($value));
        }

        unset(
            $data['date_of_birth'],
            $data['gender'],
            $data['address'],
            $data['emergency_contact'],
            $data['bank_name'],
            $data['bank_account_number'],
            $data['ifsc_code'],
            $data['pan_number'],
            $data['uan_number'],
            $data['documents_note'],
            $data['attendance_mode'],
        );

        return $related;
    }

    private function syncRelatedWorkflowData(Employee $employee, array $related): void
    {
        if (! empty($related['reporting_manager_id'])) {
            ReportingHierarchy::updateOrCreate(
                ['employee_id' => $employee->id, 'reporting_type' => 'primary'],
                [
                    'manager_id' => $related['reporting_manager_id'],
                    'effective_from' => $employee->hired_at ?? now()->toDateString(),
                    'is_active' => true,
                    'created_by' => $employee->created_by,
                ]
            );
        }

        if (! empty($related['shift_id'])) {
            ShiftAssignment::updateOrCreate(
                ['employee_id' => $employee->id, 'shift_id' => $related['shift_id']],
                [
                    'effective_from' => $employee->hired_at ?? now()->toDateString(),
                    'is_active' => true,
                    'created_by' => $employee->created_by,
                ]
            );
        }
    }
}
