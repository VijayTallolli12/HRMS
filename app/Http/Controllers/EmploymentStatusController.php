<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmploymentStatusRequest;
use App\Http\Requests\UpdateEmploymentStatusRequest;
use App\Models\EmploymentStatus;
use App\Services\EmploymentStatusService;
use Illuminate\Http\Request;

class EmploymentStatusController extends Controller
{
    public function __construct(private readonly EmploymentStatusService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', EmploymentStatus::class);

        $employmentStatuses = $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('employment-statuses.index', compact('employmentStatuses'));
    }

    public function create()
    {
        $this->authorize('create', EmploymentStatus::class);

        return view('employment-statuses.create');
    }

    public function store(StoreEmploymentStatusRequest $request)
    {
        $this->authorize('create', EmploymentStatus::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $employmentStatus = $this->service->create($data);

        return redirect()
            ->route('employment-statuses.show', $employmentStatus)
            ->with('success', 'Employment status created successfully.');
    }

    public function show(EmploymentStatus $employmentStatus)
    {
        $this->authorize('view', $employmentStatus);

        return view('employment-statuses.show', compact('employmentStatus'));
    }

    public function edit(EmploymentStatus $employmentStatus)
    {
        $this->authorize('update', $employmentStatus);

        return view('employment-statuses.edit', compact('employmentStatus'));
    }

    public function update(UpdateEmploymentStatusRequest $request, EmploymentStatus $employmentStatus)
    {
        $this->authorize('update', $employmentStatus);
        $this->service->update($employmentStatus, $request->validated());

        return redirect()
            ->route('employment-statuses.show', $employmentStatus)
            ->with('success', 'Employment status updated successfully.');
    }

    public function destroy(EmploymentStatus $employmentStatus)
    {
        $this->authorize('delete', $employmentStatus);
        $this->service->delete($employmentStatus);

        return redirect()
            ->route('employment-statuses.index')
            ->with('success', 'Employment status deleted successfully.');
    }
}
