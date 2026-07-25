<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkScheduleRequest;
use App\Http\Requests\UpdateWorkScheduleRequest;
use App\Models\Organization;
use App\Models\WorkSchedule;
use App\Services\WorkScheduleService;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function __construct(private readonly WorkScheduleService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', WorkSchedule::class);

        $organizations = Organization::orderBy('name')->get();
        $organizationId = $request->input('organization_id');

        $workSchedules = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('work-schedules.index', compact('workSchedules', 'organizations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', WorkSchedule::class);

        $organizations = Organization::orderBy('name')->get();

        return view('work-schedules.create', compact('organizations'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreWorkScheduleRequest $request)
    {
        $this->authorize('create', WorkSchedule::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $workSchedule = $this->service->create($data);

        return redirect()
            ->route('work-schedules.show', $workSchedule)
            ->with('success', 'Work schedule created successfully.');
    }

    public function show(WorkSchedule $workSchedule)
    {
        $this->authorize('view', $workSchedule);
        $workSchedule->load('organization');

        return view('work-schedules.show', compact('workSchedule'));
    }

    public function edit(WorkSchedule $workSchedule)
    {
        $this->authorize('update', $workSchedule);
        $organizations = Organization::orderBy('name')->get();

        return view('work-schedules.edit', compact('workSchedule', 'organizations'));
    }

    public function update(UpdateWorkScheduleRequest $request, WorkSchedule $workSchedule)
    {
        $this->authorize('update', $workSchedule);
        $this->service->update($workSchedule, $request->validated());

        return redirect()
            ->route('work-schedules.show', $workSchedule)
            ->with('success', 'Work schedule updated successfully.');
    }

    public function destroy(WorkSchedule $workSchedule)
    {
        $this->authorize('delete', $workSchedule);
        $this->service->delete($workSchedule);

        return redirect()
            ->route('work-schedules.index')
            ->with('success', 'Work schedule deleted successfully.');
    }
}
