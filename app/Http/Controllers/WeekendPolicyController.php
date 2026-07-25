<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeekendPolicyRequest;
use App\Http\Requests\UpdateWeekendPolicyRequest;
use App\Models\Organization;
use App\Models\WeekendPolicy;
use App\Services\WeekendPolicyService;
use Illuminate\Http\Request;

class WeekendPolicyController extends Controller
{
    public function __construct(private readonly WeekendPolicyService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', WeekendPolicy::class);

        $organizations = Organization::orderBy('name')->get();
        $organizationId = $request->input('organization_id');

        $weekendPolicies = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('weekend-policies.index', compact('weekendPolicies', 'organizations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', WeekendPolicy::class);

        $organizations = Organization::orderBy('name')->get();

        return view('weekend-policies.create', compact('organizations'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreWeekendPolicyRequest $request)
    {
        $this->authorize('create', WeekendPolicy::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $weekendPolicy = $this->service->create($data);

        return redirect()
            ->route('weekend-policies.show', $weekendPolicy)
            ->with('success', 'Weekend policy created successfully.');
    }

    public function show(WeekendPolicy $weekendPolicy)
    {
        $this->authorize('view', $weekendPolicy);
        $weekendPolicy->load('organization');

        return view('weekend-policies.show', compact('weekendPolicy'));
    }

    public function edit(WeekendPolicy $weekendPolicy)
    {
        $this->authorize('update', $weekendPolicy);
        $organizations = Organization::orderBy('name')->get();

        return view('weekend-policies.edit', compact('weekendPolicy', 'organizations'));
    }

    public function update(UpdateWeekendPolicyRequest $request, WeekendPolicy $weekendPolicy)
    {
        $this->authorize('update', $weekendPolicy);
        $this->service->update($weekendPolicy, $request->validated());

        return redirect()
            ->route('weekend-policies.show', $weekendPolicy)
            ->with('success', 'Weekend policy updated successfully.');
    }

    public function destroy(WeekendPolicy $weekendPolicy)
    {
        $this->authorize('delete', $weekendPolicy);
        $this->service->delete($weekendPolicy);

        return redirect()
            ->route('weekend-policies.index')
            ->with('success', 'Weekend policy deleted successfully.');
    }
}
