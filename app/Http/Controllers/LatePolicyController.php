<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLatePolicyRequest;
use App\Http\Requests\UpdateLatePolicyRequest;
use App\Models\LatePolicy;
use App\Models\Organization;
use App\Services\LatePolicyService;
use Illuminate\Http\Request;

class LatePolicyController extends Controller
{
    public function __construct(private readonly LatePolicyService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', LatePolicy::class);

        $organizations = Organization::orderBy('name')->get();
        $organizationId = $request->input('organization_id');

        $latePolicies = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('late-policies.index', compact('latePolicies', 'organizations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', LatePolicy::class);

        $organizations = Organization::orderBy('name')->get();

        return view('late-policies.create', compact('organizations'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreLatePolicyRequest $request)
    {
        $this->authorize('create', LatePolicy::class);

        $latePolicy = $this->service->create($request->validated());

        return redirect()
            ->route('late-policies.show', $latePolicy)
            ->with('success', 'Late policy created successfully.');
    }

    public function show($latePolicy)
    {
        $latePolicy = is_numeric($latePolicy) ? $this->service->find($latePolicy) : $latePolicy;
        $this->authorize('view', $latePolicy);
        $latePolicy->load('organization');

        return view('late-policies.show', compact('latePolicy'));
    }

    public function edit($latePolicy)
    {
        $latePolicy = is_numeric($latePolicy) ? $this->service->find($latePolicy) : $latePolicy;
        $this->authorize('update', $latePolicy);
        $organizations = Organization::orderBy('name')->get();

        return view('late-policies.edit', compact('latePolicy', 'organizations'));
    }

    public function update(UpdateLatePolicyRequest $request, $latePolicy)
    {
        $latePolicy = is_numeric($latePolicy) ? $this->service->find($latePolicy) : $latePolicy;
        $this->authorize('update', $latePolicy);
        $this->service->update($latePolicy, $request->validated());

        return redirect()
            ->route('late-policies.show', $latePolicy)
            ->with('success', 'Late policy updated successfully.');
    }

    public function destroy($latePolicy)
    {
        $latePolicy = is_numeric($latePolicy) ? $this->service->find($latePolicy) : $latePolicy;
        $this->authorize('delete', $latePolicy);
        $this->service->delete($latePolicy);

        return redirect()
            ->route('late-policies.index')
            ->with('success', 'Late policy deleted successfully.');
    }
}
