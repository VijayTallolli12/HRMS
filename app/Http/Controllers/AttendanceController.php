<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Imports\AttendanceImport;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Organization;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function import(Request $request)
    {
        $this->authorize('create', Attendance::class);

        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new AttendanceImport($request->user()->id), $request->file('import_file'));

            return redirect()
                ->route('attendances.index')
                ->with('success', 'Attendance records imported successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Import failed: '.$e->getMessage());
        }
    }

    public function dailyRegister(Request $request)
    {
        $this->authorize('viewAny', Attendance::class);

        $date = $request->input('date', today()->format('Y-m-d'));
        $organizations = Organization::orderBy('name')->get();

        $query = Employee::with(['attendances' => function ($q) use ($date) {
            $q->where('date', $date);
        }])->where('status', 'active');

        if ($request->input('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        $employees = $query->orderBy('first_name')->get();

        $presentCount = $employees->filter(fn ($e) => $e->attendances->isNotEmpty() && $e->attendances->first()->status !== 'absent')->count();
        $absentCount = $employees->filter(fn ($e) => $e->attendances->isEmpty() || $e->attendances->first()->status === 'absent')->count();
        $lateCount = $employees->filter(fn ($e) => $e->attendances->isNotEmpty() && $e->attendances->first()->status === 'late')->count();

        return view('attendances.daily-register', compact('employees', 'date', 'organizations', 'presentCount', 'absentCount', 'lateCount'));
    }
}
