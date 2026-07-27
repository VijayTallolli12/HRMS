<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\Branch;
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

        $branches = Branch::orderBy('name')->get();
        $branchId = $request->input('branch_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $status = $request->input('status');

        $attendances = $this->service->paginate(
            $request->integer('per_page', 15),
            $request->input('search'),
            $dateFrom,
            $dateTo,
            $status,
        );

        return view('attendances.index', compact('attendances', 'branches', 'branchId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Attendance::class);

        $organizations = Organization::orderBy('name')->get();
        $employees = Employee::where('status', 'active')->orderBy('first_name')->limit(200)->get();

        return view('attendances.create', compact('employees', 'organizations'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $this->authorize('create', Attendance::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['source'] = 'manual';

        $attendance = $this->service->create($data);

        return redirect()
            ->route('attendances.show', $attendance)
            ->with('success', 'Attendance record created successfully.');
    }

    public function show($attendance)
    {
        $attendance = is_numeric($attendance) ? $this->service->find($attendance) : $attendance;
        $this->authorize('view', $attendance);
        $attendance->load(['organization', 'employee', 'adjustments', 'importBatch']);

        return view('attendances.show', compact('attendance'));
    }

    public function edit($attendance)
    {
        $attendance = is_numeric($attendance) ? $this->service->find($attendance) : $attendance;
        $this->authorize('update', $attendance);

        $organizations = Organization::orderBy('name')->get();
        $employees = Employee::where('status', 'active')->orderBy('first_name')->limit(200)->get();

        return view('attendances.edit', compact('attendance', 'employees', 'organizations'));
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

    public function dailyRegister(Request $request)
    {
        $this->authorize('viewAny', Attendance::class);

        $date = $request->input('date', today()->format('Y-m-d'));
        $branches = Branch::orderBy('name')->get();
        $branchId = $request->input('branch_id');

        $query = Employee::with(['attendances' => function ($q) use ($date) {
            $q->where('date', $date);
        }])->where('status', 'active');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $employees = $query->orderBy('first_name')->get();

        $presentCount = $employees->filter(fn ($e) => $e->attendances->isNotEmpty() && $e->attendances->first()->status !== 'absent')->count();
        $absentCount = $employees->filter(fn ($e) => $e->attendances->isEmpty() || $e->attendances->first()->status === 'absent')->count();
        $lateCount = $employees->filter(fn ($e) => $e->attendances->isNotEmpty() && $e->attendances->first()->status === 'late')->count();
        $totalHours = $employees->sum(fn ($e) => $e->attendances->first()->hours_worked ?? 0);

        return view('attendances.daily-register', compact(
            'employees', 'date', 'branches', 'branchId',
            'presentCount', 'absentCount', 'lateCount', 'totalHours'
        ));
    }
}
