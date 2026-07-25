<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShiftAssignmentRequest;
use App\Http\Requests\UpdateShiftAssignmentRequest;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Services\ShiftAssignmentService;
use Illuminate\Http\Request;

class ShiftAssignmentController extends Controller
{
    public function __construct(private readonly ShiftAssignmentService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', ShiftAssignment::class);

        $employees = Employee::orderBy('first_name')->get();
        $shifts = Shift::orderBy('name')->get();
        $employeeId = $request->input('employee_id');

        $shiftAssignments = $employeeId
            ? $this->service->paginateByEmployee($employeeId, $request->integer('per_page', 15), $request->input('search'))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('shift-assignments.index', compact('shiftAssignments', 'employees', 'shifts', 'employeeId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', ShiftAssignment::class);

        $employees = Employee::orderBy('first_name')->get();
        $shifts = Shift::orderBy('name')->get();

        return view('shift-assignments.create', compact('employees', 'shifts'));
    }

    public function store(StoreShiftAssignmentRequest $request)
    {
        $this->authorize('create', ShiftAssignment::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $shiftAssignment = $this->service->create($data);

        return redirect()
            ->route('shift-assignments.show', $shiftAssignment)
            ->with('success', 'Shift assignment created successfully.');
    }

    public function show(ShiftAssignment $shiftAssignment)
    {
        $this->authorize('view', $shiftAssignment);
        $shiftAssignment->load(['employee', 'shift']);

        return view('shift-assignments.show', compact('shiftAssignment'));
    }

    public function edit(ShiftAssignment $shiftAssignment)
    {
        $this->authorize('update', $shiftAssignment);
        $employees = Employee::orderBy('first_name')->get();
        $shifts = Shift::orderBy('name')->get();

        return view('shift-assignments.edit', compact('shiftAssignment', 'employees', 'shifts'));
    }

    public function update(UpdateShiftAssignmentRequest $request, ShiftAssignment $shiftAssignment)
    {
        $this->authorize('update', $shiftAssignment);
        $this->service->update($shiftAssignment, $request->validated());

        return redirect()
            ->route('shift-assignments.show', $shiftAssignment)
            ->with('success', 'Shift assignment updated successfully.');
    }

    public function destroy(ShiftAssignment $shiftAssignment)
    {
        $this->authorize('delete', $shiftAssignment);
        $this->service->delete($shiftAssignment);

        return redirect()
            ->route('shift-assignments.index')
            ->with('success', 'Shift assignment deleted successfully.');
    }
}
