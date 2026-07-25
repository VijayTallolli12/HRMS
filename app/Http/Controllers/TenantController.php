<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function __construct(private readonly TenantService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Tenant::class);

        $tenants = $this->service->paginate(
            perPage: $request->integer('per_page', 15),
            search: $request->input('search')
        );

        return view('tenants.index', compact('tenants'));
    }

    public function create()
    {
        $this->authorize('create', Tenant::class);

        return view('tenants.create');
    }

    public function store(StoreTenantRequest $request)
    {
        $this->authorize('create', Tenant::class);

        $tenant = $this->service->create($request->validated());

        return redirect()
            ->route('tenants.show', $tenant)
            ->with('success', 'Tenant created successfully.');
    }

    public function show(Tenant $tenant)
    {
        $this->authorize('view', $tenant);
        $tenant->load('users');

        return view('tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        $this->authorize('update', $tenant);

        return view('tenants.edit', compact('tenant'));
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant)
    {
        $this->authorize('update', $tenant);
        $this->service->update($tenant, $request->validated());

        return redirect()
            ->route('tenants.show', $tenant)
            ->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        $this->authorize('delete', $tenant);
        $this->service->delete($tenant);

        return redirect()
            ->route('tenants.index')
            ->with('success', 'Tenant deleted successfully.');
    }
}
