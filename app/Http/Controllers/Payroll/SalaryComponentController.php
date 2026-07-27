<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\SalaryComponent;
use App\Services\Payroll\SalaryComponentService;
use Illuminate\Http\Request;

class SalaryComponentController extends Controller
{
    public function __construct(private readonly SalaryComponentService $service) {}

    public function index(Request $request)
    {
        $this->authorize('view-salary-component');

        $components = $this->service->paginate(
            perPage: $request->integer('per_page', 15),
            search: $request->input('search')
        );

        return view('payroll.salary-components.index', compact('components'));
    }

    public function create()
    {
        $this->authorize('create-salary-component');

        return view('payroll.salary-components.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create-salary-component');

        $data = $request->validate([
            'organization_id' => 'nullable|exists:organizations,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:salary_components,code',
            'type' => 'required|in:earning,deduction,benefit',
            'calculation_type' => 'required|in:fixed,percentage',
            'default_value' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $component = $this->service->create($data);

        return redirect()
            ->route('payroll.salary-components.index')
            ->with('success', 'Salary component created successfully.');
    }

    public function show($salary_component)
    {
        $this->authorize('view-salary-component');

        $component = SalaryComponent::findOrFail($salary_component);
        $component->load('organization');

        return view('payroll.salary-components.show', ['comp' => $component]);
    }

    public function edit($salary_component)
    {
        $this->authorize('update-salary-component');

        $component = SalaryComponent::findOrFail($salary_component);

        return view('payroll.salary-components.edit', ['comp' => $component]);
    }

    public function update(Request $request, $salary_component)
    {
        $this->authorize('update-salary-component');

        $component = SalaryComponent::findOrFail($salary_component);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:salary_components,code,'.$component->id,
            'type' => 'required|in:earning,deduction,benefit',
            'calculation_type' => 'required|in:fixed,percentage',
            'default_value' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $this->service->update($component, $data);

        return redirect()
            ->route('payroll.salary-components.index')
            ->with('success', 'Salary component updated successfully.');
    }

    public function destroy($salary_component)
    {
        $this->authorize('delete-salary-component');

        $component = SalaryComponent::findOrFail($salary_component);
        $this->service->delete($component);

        return redirect()
            ->route('payroll.salary-components.index')
            ->with('success', 'Salary component deleted successfully.');
    }
}
