<?php

namespace App\Events;

use App\Models\Employee;
use Illuminate\Foundation\Events\Dispatchable;

class EmployeeUpdated
{
    use Dispatchable;

    public function __construct(public readonly Employee $employee) {}
}
