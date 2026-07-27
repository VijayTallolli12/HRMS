<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use App\Models\Branch;
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

        $organizations = $this->organizationsFor($request);
        $branches = $this->branchesFor($request);
        $departments = $this->departmentsFor($request);
        $organizationId = $request->input('organization_id');
        $filters = $request->only(['branch_id', 'department_id', 'status']);

        $designations = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'), array_filter($filters))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'), array_filter($filters));

        return view('designations.index', compact('designations', 'organizations', 'branches', 'departments', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Designation::class);

        $organizations = $this->organizationsFor($request);
        $branches = $this->branchesFor($request);
        $departments = $this->departmentsFor($request);

        return view('designations.create', compact('organizations', 'branches', 'departments'))
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
        $designation->load(['department', 'branch', 'organization']);

        return view('designations.show', compact('designation'));
    }

    public function edit(Designation $designation)
    {
        $this->authorize('update', $designation);
        $request = request();
        $organizations = $this->organizationsFor($request);
        $branches = $this->branchesFor($request);
        $departments = $this->departmentsFor($request);

        return view('designations.edit', compact('designation', 'organizations', 'branches', 'departments'));
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
        try {
            $this->service->delete($designation);
        } catch (\DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation deleted successfully.');
    }

    private function organizationsFor(Request $request)
    {
        $query = Organization::orderBy('name');

        if ($request->user()->isBranchAdmin()) {
            $query->where('id', $request->user()->organization_id);
        }

        return $query->get();
    }

    private function branchesFor(Request $request)
    {
        $query = Branch::orderBy('name');

        if ($request->user()->isBranchAdmin()) {
            $query->where('id', $request->user()->branch_id);
        }

        return $query->get();
    }

    private function departmentsFor(Request $request)
    {
        $query = Department::orderBy('name');

        if ($request->user()->isBranchAdmin()) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        return $query->get();
    }
}
