<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Organization;
use App\Services\LeaveService;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function __construct(private readonly LeaveService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Leave::class);

        $organizations = Organization::orderBy('name')->get();
        $organizationId = $request->input('organization_id');
        $status = $request->input('status');

        $leaves = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'), $status)
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'), $status);

        return view('leaves.index', compact('leaves', 'organizations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Leave::class);

        $organizations = Organization::orderBy('name')->get();

        $employeesQuery = Employee::orderBy('first_name');
        if ($request->input('organization_id')) {
            $employeesQuery->where('organization_id', $request->input('organization_id'));
        }
        $employees = $employeesQuery->limit(200)->get();

        return view('leaves.create', compact('organizations', 'employees'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreLeaveRequest $request)
    {
        $this->authorize('create', Leave::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $leave = $this->service->create($data);

        return redirect()
            ->route('leaves.show', $leave)
            ->with('success', 'Leave request created successfully.');
    }

    public function show($leave)
    {
        $leave = is_numeric($leave) ? $this->service->find($leave) : $leave;
        $this->authorize('view', $leave);
        $leave->load(['organization', 'employee', 'approver', 'creator']);

        return view('leaves.show', compact('leave'));
    }

    public function edit($leave)
    {
        $leave = is_numeric($leave) ? $this->service->find($leave) : $leave;
        $this->authorize('update', $leave);
        $organizations = Organization::orderBy('name')->get();

        $employeesQuery = Employee::orderBy('first_name');
        if ($leave->organization_id) {
            $employeesQuery->where('organization_id', $leave->organization_id);
        }
        $employees = $employeesQuery->limit(200)->get();

        return view('leaves.edit', compact('leave', 'organizations', 'employees'));
    }

    public function update(UpdateLeaveRequest $request, $leave)
    {
        $leave = is_numeric($leave) ? $this->service->find($leave) : $leave;
        $this->authorize('update', $leave);
        $this->service->update($leave, $request->validated());

        return redirect()
            ->route('leaves.show', $leave)
            ->with('success', 'Leave request updated successfully.');
    }

    public function destroy($leave)
    {
        $leave = is_numeric($leave) ? $this->service->find($leave) : $leave;
        $this->authorize('delete', $leave);
        $this->service->delete($leave);

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave request deleted successfully.');
    }
}
