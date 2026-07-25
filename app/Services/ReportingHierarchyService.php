<?php

namespace App\Services;

use App\Models\ReportingHierarchy;
use App\Repositories\ReportingHierarchyRepository;

class ReportingHierarchyService
{
    public function __construct(private readonly ReportingHierarchyRepository $repo) {}

    public function create(array $data): ReportingHierarchy
    {
        return $this->repo->create($data);
    }

    public function find(int $id): ?ReportingHierarchy
    {
        return $this->repo->find($id);
    }

    public function paginate(int $perPage = 15, ?string $search = null)
    {
        return $this->repo->paginate($perPage, $search);
    }

    public function update(ReportingHierarchy $reportingHierarchy, array $data): ReportingHierarchy
    {
        return $this->repo->update($reportingHierarchy, $data);
    }

    public function delete(ReportingHierarchy $reportingHierarchy): bool
    {
        return $this->repo->delete($reportingHierarchy);
    }

    public function count(): int
    {
        return $this->repo->count();
    }
}
