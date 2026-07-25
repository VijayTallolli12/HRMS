<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\Organization;
use App\Services\LeaveTypeService;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function __construct(private readonly LeaveTypeService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', LeaveType::class);

        $organizations = Organization::orderBy('name')->get();
        $organizationId = $request->input('organization_id');

        $leaveTypes = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('leave-types.index', compact('leaveTypes', 'organizations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', LeaveType::class);

        $organizations = Organization::orderBy('name')->get();

        return view('leave-types.create', compact('organizations'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', LeaveType::class);

        $validated = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|string|max:255',
            'days_per_year' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $leaveType = $this->service->create($validated);

        return redirect()
            ->route('leave-types.show', $leaveType)
            ->with('success', 'Leave type created successfully.');
    }

    public function show(LeaveType $leaveType)
    {
        $this->authorize('view', $leaveType);
        $leaveType->load('organization');

        return view('leave-types.show', compact('leaveType'));
    }

    public function edit(LeaveType $leaveType)
    {
        $this->authorize('update', $leaveType);
        $organizations = Organization::orderBy('name')->get();

        return view('leave-types.edit', compact('leaveType', 'organizations'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $this->authorize('update', $leaveType);

        $validated = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|string|max:255',
            'days_per_year' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $this->service->update($leaveType, $validated);

        return redirect()
            ->route('leave-types.show', $leaveType)
            ->with('success', 'Leave type updated successfully.');
    }

    public function destroy(LeaveType $leaveType)
    {
        $this->authorize('delete', $leaveType);
        $this->service->delete($leaveType);

        return redirect()
            ->route('leave-types.index')
            ->with('success', 'Leave type deleted successfully.');
    }
}
