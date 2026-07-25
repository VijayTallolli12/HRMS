<?php

namespace App\Services;

use App\Repositories\OrganizationRepository;

class OrganizationService
{
    public function __construct(private OrganizationRepository $repo)
    {
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }
}
