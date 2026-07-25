<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use App\Models\Organization;
use App\Models\Shift;
use App\Services\ShiftService;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function __construct(private readonly ShiftService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Shift::class);

        $organizations = Organization::orderBy('name')->get();
        $organizationId = $request->input('organization_id');

        $shifts = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('shifts.index', compact('shifts', 'organizations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Shift::class);

        $organizations = Organization::orderBy('name')->get();

        return view('shifts.create', compact('organizations'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreShiftRequest $request)
    {
        $this->authorize('create', Shift::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $shift = $this->service->create($data);

        return redirect()
            ->route('shifts.show', $shift)
            ->with('success', 'Shift created successfully.');
    }

    public function show(Shift $shift)
    {
        $this->authorize('view', $shift);
        $shift->load('organization');

        return view('shifts.show', compact('shift'));
    }

    public function edit(Shift $shift)
    {
        $this->authorize('update', $shift);
        $organizations = Organization::orderBy('name')->get();

        return view('shifts.edit', compact('shift', 'organizations'));
    }

    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        $this->authorize('update', $shift);
        $this->service->update($shift, $request->validated());

        return redirect()
            ->route('shifts.show', $shift)
            ->with('success', 'Shift updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        $this->authorize('delete', $shift);
        $this->service->delete($shift);

        return redirect()
            ->route('shifts.index')
            ->with('success', 'Shift deleted successfully.');
    }
}
