<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Organization;
use App\Services\DesignationService;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function __construct(private readonly DesignationService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Designation::class);

        $organizations = Organization::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $organizationId = $request->input('organization_id');

        $designations = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('designations.index', compact('designations', 'organizations', 'departments', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Designation::class);

        $organizations = Organization::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('designations.create', compact('organizations', 'departments'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreDesignationRequest $request)
    {
        $this->authorize('create', Designation::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $designation = $this->service->create($data);

        return redirect()
            ->route('designations.show', $designation)
            ->with('success', 'Designation created successfully.');
    }

    public function show(Designation $designation)
    {
        $this->authorize('view', $designation);
        $designation->load(['department', 'organization', 'employees']);

        return view('designations.show', compact('designation'));
    }

    public function edit(Designation $designation)
    {
        $this->authorize('update', $designation);
        $organizations = Organization::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('designations.edit', compact('designation', 'organizations', 'departments'));
    }

    public function update(UpdateDesignationRequest $request, Designation $designation)
    {
        $this->authorize('update', $designation);
        $this->service->update($designation, $request->validated());

        return redirect()
            ->route('designations.show', $designation)
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        $this->authorize('delete', $designation);
        $this->service->delete($designation);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation deleted successfully.');
    }
}
