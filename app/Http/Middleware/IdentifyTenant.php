<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        // Simple tenant identification: header `X-Tenant-ID` or env default
        $tenantId = $request->header('X-Tenant-ID') ?? env('APP_TENANT_ID');

        if ($tenantId) {
            $tenant = Tenant::find($tenantId);
            if ($tenant) {
                app()->instance(Tenant::class, $tenant);
                // Optionally set a config flag for tenant
                Config::set('app.tenant', $tenant->toArray());
            }
        }

        return $next($request);
    }
}
