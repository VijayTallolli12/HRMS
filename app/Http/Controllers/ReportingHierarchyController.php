<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportingHierarchyRequest;
use App\Http\Requests\UpdateReportingHierarchyRequest;
use App\Models\Employee;
use App\Models\ReportingHierarchy;
use App\Services\ReportingHierarchyService;
use Illuminate\Http\Request;

class ReportingHierarchyController extends Controller
{
    public function __construct(private readonly ReportingHierarchyService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', ReportingHierarchy::class);

        $employees = Employee::orderBy('first_name')->get();
        $reportingHierarchies = $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('reporting-hierarchies.index', compact('reportingHierarchies', 'employees'));
    }

    public function create()
    {
        $this->authorize('create', ReportingHierarchy::class);

        $employees = Employee::orderBy('first_name')->get();

        return view('reporting-hierarchies.create', compact('employees'));
    }

    public function store(StoreReportingHierarchyRequest $request)
    {
        $this->authorize('create', ReportingHierarchy::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $reportingHierarchy = $this->service->create($data);

        return redirect()
            ->route('reporting-hierarchies.show', $reportingHierarchy)
            ->with('success', 'Reporting hierarchy created successfully.');
    }

    public function show(ReportingHierarchy $reportingHierarchy)
    {
        $this->authorize('view', $reportingHierarchy);
        $reportingHierarchy->load(['employee', 'manager']);

        return view('reporting-hierarchies.show', compact('reportingHierarchy'));
    }

    public function edit(ReportingHierarchy $reportingHierarchy)
    {
        $this->authorize('update', $reportingHierarchy);
        $employees = Employee::orderBy('first_name')->get();

        return view('reporting-hierarchies.edit', compact('reportingHierarchy', 'employees'));
    }

    public function update(UpdateReportingHierarchyRequest $request, ReportingHierarchy $reportingHierarchy)
    {
        $this->authorize('update', $reportingHierarchy);
        $this->service->update($reportingHierarchy, $request->validated());

        return redirect()
            ->route('reporting-hierarchies.show', $reportingHierarchy)
            ->with('success', 'Reporting hierarchy updated successfully.');
    }

    public function destroy(ReportingHierarchy $reportingHierarchy)
    {
        $this->authorize('delete', $reportingHierarchy);
        $this->service->delete($reportingHierarchy);

        return redirect()
            ->route('reporting-hierarchies.index')
            ->with('success', 'Reporting hierarchy deleted successfully.');
    }
}
