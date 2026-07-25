<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOvertimeRequestRequest;
use App\Http\Requests\UpdateOvertimeRequestRequest;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\OvertimeRequest;
use App\Services\OvertimeRequestService;
use Illuminate\Http\Request;

class OvertimeRequestController extends Controller
{
    public function __construct(private readonly OvertimeRequestService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', OvertimeRequest::class);

        $organizations = Organization::orderBy('name')->get();
        $organizationId = $request->input('organization_id');
        $status = $request->input('status');

        $overtimeRequests = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'), $status)
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'), $status);

        return view('overtime-requests.index', compact('overtimeRequests', 'organizations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', OvertimeRequest::class);

        $organizations = Organization::orderBy('name')->get();
        $employees = Employee::orderBy('first_name')->get();

        return view('overtime-requests.create', compact('organizations', 'employees'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreOvertimeRequestRequest $request)
    {
        $this->authorize('create', OvertimeRequest::class);

        $data = $request->validated();
        $data['requested_by'] = $request->user()->id;

        $overtimeRequest = $this->service->create($data);

        return redirect()
            ->route('overtime-requests.show', $overtimeRequest)
            ->with('success', 'Overtime request submitted successfully.');
    }

    public function show($overtimeRequest)
    {
        $overtimeRequest = is_numeric($overtimeRequest) ? $this->service->find($overtimeRequest) : $overtimeRequest;
        $this->authorize('view', $overtimeRequest);
        $overtimeRequest->load(['organization', 'employee', 'requester', 'approver']);

        return view('overtime-requests.show', compact('overtimeRequest'));
    }

    public function edit($overtimeRequest)
    {
        $overtimeRequest = is_numeric($overtimeRequest) ? $this->service->find($overtimeRequest) : $overtimeRequest;
        $this->authorize('update', $overtimeRequest);
        $organizations = Organization::orderBy('name')->get();
        $employees = Employee::orderBy('first_name')->get();

        return view('overtime-requests.edit', compact('overtimeRequest', 'organizations', 'employees'));
    }

    public function update(UpdateOvertimeRequestRequest $request, $overtimeRequest)
    {
        $overtimeRequest = is_numeric($overtimeRequest) ? $this->service->find($overtimeRequest) : $overtimeRequest;
        $this->authorize('update', $overtimeRequest);
        $data = $request->validated();
        if ($data['status'] === 'approved') {
            $data['approved_by'] = $request->user()->id;
        }
        $this->service->update($overtimeRequest, $data);

        return redirect()
            ->route('overtime-requests.show', $overtimeRequest)
            ->with('success', 'Overtime request updated successfully.');
    }

    public function destroy($overtimeRequest)
    {
        $overtimeRequest = is_numeric($overtimeRequest) ? $this->service->find($overtimeRequest) : $overtimeRequest;
        $this->authorize('delete', $overtimeRequest);
        $this->service->delete($overtimeRequest);

        return redirect()
            ->route('overtime-requests.index')
            ->with('success', 'Overtime request deleted successfully.');
    }
}
