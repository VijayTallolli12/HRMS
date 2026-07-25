<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmploymentTypeRequest;
use App\Http\Requests\UpdateEmploymentTypeRequest;
use App\Models\EmploymentType;
use App\Services\EmploymentTypeService;
use Illuminate\Http\Request;

class EmploymentTypeController extends Controller
{
    public function __construct(private readonly EmploymentTypeService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', EmploymentType::class);

        $employmentTypes = $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('employment-types.index', compact('employmentTypes'));
    }

    public function create()
    {
        $this->authorize('create', EmploymentType::class);

        return view('employment-types.create');
    }

    public function store(StoreEmploymentTypeRequest $request)
    {
        $this->authorize('create', EmploymentType::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $employmentType = $this->service->create($data);

        return redirect()
            ->route('employment-types.show', $employmentType)
            ->with('success', 'Employment type created successfully.');
    }

    public function show(EmploymentType $employmentType)
    {
        $this->authorize('view', $employmentType);

        return view('employment-types.show', compact('employmentType'));
    }

    public function edit(EmploymentType $employmentType)
    {
        $this->authorize('update', $employmentType);

        return view('employment-types.edit', compact('employmentType'));
    }

    public function update(UpdateEmploymentTypeRequest $request, EmploymentType $employmentType)
    {
        $this->authorize('update', $employmentType);
        $this->service->update($employmentType, $request->validated());

        return redirect()
            ->route('employment-types.show', $employmentType)
            ->with('success', 'Employment type updated successfully.');
    }

    public function destroy(EmploymentType $employmentType)
    {
        $this->authorize('delete', $employmentType);
        $this->service->delete($employmentType);

        return redirect()
            ->route('employment-types.index')
            ->with('success', 'Employment type deleted successfully.');
    }
}
