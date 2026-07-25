<?php

namespace App\Listeners;

use Illuminate\Foundation\Events\Dispatchable;

class LogActivity
{
    use Dispatchable;

    public function handle(object $event): void
    {
        // Activity is handled by the Auditable trait
    }
}
