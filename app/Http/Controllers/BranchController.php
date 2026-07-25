<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Models\Organization;
use App\Services\BranchService;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct(private readonly BranchService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Branch::class);

        $organizations = Organization::orderBy('name')->get();
        $organizationId = $request->input('organization_id');

        $branches = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('branches.index', compact('branches', 'organizations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Branch::class);

        $organizations = Organization::orderBy('name')->get();

        return view('branches.create', compact('organizations'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreBranchRequest $request)
    {
        $this->authorize('create', Branch::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $branch = $this->service->create($data);

        return redirect()
            ->route('branches.show', $branch)
            ->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        $this->authorize('view', $branch);
        $branch->load(['organization', 'employees']);

        return view('branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        $this->authorize('update', $branch);
        $organizations = Organization::orderBy('name')->get();

        return view('branches.edit', compact('branch', 'organizations'));
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $this->authorize('update', $branch);
        $this->service->update($branch, $request->validated());

        return redirect()
            ->route('branches.show', $branch)
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $this->authorize('delete', $branch);
        $this->service->delete($branch);

        return redirect()
            ->route('branches.index')
            ->with('success', 'Branch deleted successfully.');
    }
}
