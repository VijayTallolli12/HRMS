<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Organization;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Attendance::class);

        $organizations = Organization::orderBy('name')->get();
        $organizationId = $request->input('organization_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $status = $request->input('status');

        $attendances = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'), $dateFrom, $dateTo, $status)
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'), $dateFrom, $dateTo, $status);

        return view('attendances.index', compact('attendances', 'organizations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Attendance::class);

        $organizations = Organization::orderBy('name')->get();

        $employeesQuery = Employee::orderBy('first_name');
        if ($request->input('organization_id')) {
            $employeesQuery->where('organization_id', $request->input('organization_id'));
        }
        $employees = $employeesQuery->limit(200)->get();

        return view('attendances.create', compact('organizations', 'employees'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $this->authorize('create', Attendance::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $attendance = $this->service->create($data);

        return redirect()
            ->route('attendances.show', $attendance)
            ->with('success', 'Attendance record created successfully.');
    }

    public function show($attendance)
    {
        $attendance = is_numeric($attendance) ? $this->service->find($attendance) : $attendance;
        $this->authorize('view', $attendance);
        $attendance->load(['organization', 'employee']);

        return view('attendances.show', compact('attendance'));
    }

    public function edit($attendance)
    {
        $attendance = is_numeric($attendance) ? $this->service->find($attendance) : $attendance;
        $this->authorize('update', $attendance);
        $organizations = Organization::orderBy('name')->get();

        $employeesQuery = Employee::orderBy('first_name');
        if ($attendance->organization_id) {
            $employeesQuery->where('organization_id', $attendance->organization_id);
        }
        $employees = $employeesQuery->limit(200)->get();

        return view('attendances.edit', compact('attendance', 'organizations', 'employees'));
    }

    public function update(UpdateAttendanceRequest $request, $attendance)
    {
        $attendance = is_numeric($attendance) ? $this->service->find($attendance) : $attendance;
        $this->authorize('update', $attendance);
        $this->service->update($attendance, $request->validated());

        return redirect()
            ->route('attendances.show', $attendance)
            ->with('success', 'Attendance record updated successfully.');
    }

    public function destroy($attendance)
    {
        $attendance = is_numeric($attendance) ? $this->service->find($attendance) : $attendance;
        $this->authorize('delete', $attendance);
        $this->service->delete($attendance);

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance record deleted successfully.');
    }
}
