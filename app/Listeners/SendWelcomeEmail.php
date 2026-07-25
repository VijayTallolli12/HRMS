<?php

namespace App\Listeners;

use App\Events\EmployeeCreated;

class SendWelcomeEmail
{
    public function handle(EmployeeCreated $event): void
    {
        // Placeholder for welcome email notification
    }
}
