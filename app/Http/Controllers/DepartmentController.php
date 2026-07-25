<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Models\Organization;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(private readonly DepartmentService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Department::class);

        $organizations = Organization::orderBy('name')->get();
        $organizationId = $request->input('organization_id');

        $departments = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('departments.index', compact('departments', 'organizations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Department::class);

        $organizations = Organization::orderBy('name')->get();

        return view('departments.create', compact('organizations'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreDepartmentRequest $request)
    {
        $this->authorize('create', Department::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $department = $this->service->create($data);

        return redirect()
            ->route('departments.show', $department)
            ->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        $this->authorize('view', $department);
        $department->load(['organization', 'designations', 'employees']);

        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $this->authorize('update', $department);
        $organizations = Organization::orderBy('name')->get();

        return view('departments.edit', compact('department', 'organizations'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $this->authorize('update', $department);
        $this->service->update($department, $request->validated());

        return redirect()
            ->route('departments.show', $department)
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);
        $this->service->delete($department);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
