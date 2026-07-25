<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceAdjustmentRequest;
use App\Http\Requests\UpdateAttendanceAdjustmentRequest;
use App\Models\Attendance;
use App\Models\AttendanceAdjustment;
use App\Models\Employee;
use App\Models\Organization;
use App\Services\AttendanceAdjustmentService;
use Illuminate\Http\Request;

class AttendanceAdjustmentController extends Controller
{
    public function __construct(private readonly AttendanceAdjustmentService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', AttendanceAdjustment::class);

        $organizations = Organization::orderBy('name')->get();
        $organizationId = $request->input('organization_id');
        $status = $request->input('status');

        $adjustments = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'), $status)
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'), $status);

        return view('attendance-adjustments.index', compact('adjustments', 'organizations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', AttendanceAdjustment::class);

        $organizations = Organization::orderBy('name')->get();

        $employeesQuery = Employee::orderBy('first_name');
        $attendancesQuery = Attendance::orderBy('date', 'desc');
        if ($request->input('organization_id')) {
            $employeesQuery->where('organization_id', $request->input('organization_id'));
            $attendancesQuery->where('organization_id', $request->input('organization_id'));
        }
        $employees = $employeesQuery->limit(200)->get();
        $attendances = $attendancesQuery->limit(200)->get();

        return view('attendance-adjustments.create', compact('organizations', 'employees', 'attendances'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreAttendanceAdjustmentRequest $request)
    {
        $this->authorize('create', AttendanceAdjustment::class);

        $data = $request->validated();
        $data['requested_by'] = $request->user()->id;

        $adjustment = $this->service->create($data);

        return redirect()
            ->route('attendance-adjustments.show', $adjustment)
            ->with('success', 'Attendance adjustment request submitted successfully.');
    }

    public function show($adjustment)
    {
        $adjustment = is_numeric($adjustment) ? $this->service->find($adjustment) : $adjustment;
        $this->authorize('view', $adjustment);
        $adjustment->load(['organization', 'attendance', 'employee', 'requester', 'approver']);

        return view('attendance-adjustments.show', compact('adjustment'));
    }

    public function edit($adjustment)
    {
        $adjustment = is_numeric($adjustment) ? $this->service->find($adjustment) : $adjustment;
        $this->authorize('update', $adjustment);
        $organizations = Organization::orderBy('name')->get();

        $employeesQuery = Employee::orderBy('first_name');
        $attendancesQuery = Attendance::orderBy('date', 'desc');
        if ($adjustment->organization_id) {
            $employeesQuery->where('organization_id', $adjustment->organization_id);
            $attendancesQuery->where('organization_id', $adjustment->organization_id);
        }
        $employees = $employeesQuery->limit(200)->get();
        $attendances = $attendancesQuery->limit(200)->get();

        return view('attendance-adjustments.edit', compact('adjustment', 'organizations', 'employees', 'attendances'));
    }

    public function update(UpdateAttendanceAdjustmentRequest $request, $adjustment)
    {
        $adjustment = is_numeric($adjustment) ? $this->service->find($adjustment) : $adjustment;
        $this->authorize('update', $adjustment);
        $data = $request->validated();
        if ($data['status'] === 'approved') {
            $data['approved_by'] = $request->user()->id;
        }
        $this->service->update($adjustment, $data);

        return redirect()
            ->route('attendance-adjustments.show', $adjustment)
            ->with('success', 'Attendance adjustment updated successfully.');
    }

    public function destroy($adjustment)
    {
        $adjustment = is_numeric($adjustment) ? $this->service->find($adjustment) : $adjustment;
        $this->authorize('delete', $adjustment);
        $this->service->delete($adjustment);

        return redirect()
            ->route('attendance-adjustments.index')
            ->with('success', 'Attendance adjustment deleted successfully.');
    }
}
