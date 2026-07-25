<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeCategoryRequest;
use App\Http\Requests\UpdateEmployeeCategoryRequest;
use App\Models\EmployeeCategory;
use App\Services\EmployeeCategoryService;
use Illuminate\Http\Request;

class EmployeeCategoryController extends Controller
{
    public function __construct(private readonly EmployeeCategoryService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', EmployeeCategory::class);

        $employeeCategories = $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('employee-categories.index', compact('employeeCategories'));
    }

    public function create()
    {
        $this->authorize('create', EmployeeCategory::class);

        return view('employee-categories.create');
    }

    public function store(StoreEmployeeCategoryRequest $request)
    {
        $this->authorize('create', EmployeeCategory::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $employeeCategory = $this->service->create($data);

        return redirect()
            ->route('employee-categories.show', $employeeCategory)
            ->with('success', 'Employee category created successfully.');
    }

    public function show(EmployeeCategory $employeeCategory)
    {
        $this->authorize('view', $employeeCategory);

        return view('employee-categories.show', compact('employeeCategory'));
    }

    public function edit(EmployeeCategory $employeeCategory)
    {
        $this->authorize('update', $employeeCategory);

        return view('employee-categories.edit', compact('employeeCategory'));
    }

    public function update(UpdateEmployeeCategoryRequest $request, EmployeeCategory $employeeCategory)
    {
        $this->authorize('update', $employeeCategory);
        $this->service->update($employeeCategory, $request->validated());

        return redirect()
            ->route('employee-categories.show', $employeeCategory)
            ->with('success', 'Employee category updated successfully.');
    }

    public function destroy(EmployeeCategory $employeeCategory)
    {
        $this->authorize('delete', $employeeCategory);
        $this->service->delete($employeeCategory);

        return redirect()
            ->route('employee-categories.index')
            ->with('success', 'Employee category deleted successfully.');
    }
}
