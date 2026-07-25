<?php

namespace App\Repositories;

use App\Models\Organization;

class OrganizationRepository
{
    public function create(array $data): Organization
    {
        return Organization::create($data);
    }

    public function find(int $id): ?Organization
    {
        return Organization::find($id);
    }

    public function paginate(int $perPage = 15)
    {
        return Organization::query()->paginate($perPage);
    }
}
