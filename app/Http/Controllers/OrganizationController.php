<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organization;
use App\Services\OrganizationService;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct(private readonly OrganizationService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Organization::class);

        $organizations = $this->service->paginate(
            perPage: $request->integer('per_page', 15),
            search: $request->input('search'),
            tenantId: $request->user()->tenant_id
        );

        return view('organizations.index', compact('organizations'));
    }

    public function create()
    {
        $this->authorize('create', Organization::class);

        return view('organizations.create');
    }

    public function store(StoreOrganizationRequest $request)
    {
        $this->authorize('create', Organization::class);

        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;
        $data['created_by'] = $request->user()->id;

        $organization = $this->service->create($data);

        return redirect()
            ->route('organizations.show', $organization)
            ->with('success', 'Organization created successfully.');
    }

    public function show(Organization $organization)
    {
        $this->authorize('view', $organization);
        $organization->load(['branches', 'departments', 'employees']);

        return view('organizations.show', compact('organization'));
    }

    public function edit(Organization $organization)
    {
        $this->authorize('update', $organization);

        return view('organizations.edit', compact('organization'));
    }

    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $this->authorize('update', $organization);
        $this->service->update($organization, $request->validated());

        return redirect()
            ->route('organizations.show', $organization)
            ->with('success', 'Organization updated successfully.');
    }

    public function destroy(Organization $organization)
    {
        $this->authorize('delete', $organization);
        $this->service->delete($organization);

        return redirect()
            ->route('organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }
}
