<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Organization;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(private readonly DepartmentService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Department::class);

        $organizations = $this->organizationsFor($request);
        $branches = $this->branchesFor($request);
        $organizationId = $request->input('organization_id');
        $filters = $request->only(['branch_id', 'status']);

        $departments = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'), array_filter($filters))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'), array_filter($filters));

        return view('departments.index', compact('departments', 'organizations', 'branches', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Department::class);

        $organizations = $this->organizationsFor($request);
        $branches = $this->branchesFor($request);
        $employees = $this->employeesFor($request);

        return view('departments.create', compact('organizations', 'branches', 'employees'))
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
        $department->load(['organization', 'branch', 'head', 'designations', 'employees']);

        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $this->authorize('update', $department);
        $request = request();
        $organizations = $this->organizationsFor($request);
        $branches = $this->branchesFor($request);
        $employees = $this->employeesFor($request);

        return view('departments.edit', compact('department', 'organizations', 'branches', 'employees'));
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
        try {
            $this->service->delete($department);
        } catch (\DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted successfully.');
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

    private function employeesFor(Request $request)
    {
        $query = Employee::orderBy('first_name')->orderBy('last_name');

        if ($request->user()->isBranchAdmin()) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        return $query->get();
    }
}
