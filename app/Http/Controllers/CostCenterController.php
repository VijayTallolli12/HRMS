<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCostCenterRequest;
use App\Http\Requests\UpdateCostCenterRequest;
use App\Models\CostCenter;
use App\Models\Department;
use App\Models\Organization;
use App\Services\CostCenterService;
use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    public function __construct(private readonly CostCenterService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', CostCenter::class);

        $organizations = Organization::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $organizationId = $request->input('organization_id');

        $costCenters = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('cost-centers.index', compact('costCenters', 'organizations', 'departments', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', CostCenter::class);

        $organizations = Organization::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('cost-centers.create', compact('organizations', 'departments'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreCostCenterRequest $request)
    {
        $this->authorize('create', CostCenter::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $costCenter = $this->service->create($data);

        return redirect()
            ->route('cost-centers.show', $costCenter)
            ->with('success', 'Cost center created successfully.');
    }

    public function show(CostCenter $costCenter)
    {
        $this->authorize('view', $costCenter);
        $costCenter->load(['organization', 'department']);

        return view('cost-centers.show', compact('costCenter'));
    }

    public function edit(CostCenter $costCenter)
    {
        $this->authorize('update', $costCenter);
        $organizations = Organization::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('cost-centers.edit', compact('costCenter', 'organizations', 'departments'));
    }

    public function update(UpdateCostCenterRequest $request, CostCenter $costCenter)
    {
        $this->authorize('update', $costCenter);
        $this->service->update($costCenter, $request->validated());

        return redirect()
            ->route('cost-centers.show', $costCenter)
            ->with('success', 'Cost center updated successfully.');
    }

    public function destroy(CostCenter $costCenter)
    {
        $this->authorize('delete', $costCenter);
        $this->service->delete($costCenter);

        return redirect()
            ->route('cost-centers.index')
            ->with('success', 'Cost center deleted successfully.');
    }
}
