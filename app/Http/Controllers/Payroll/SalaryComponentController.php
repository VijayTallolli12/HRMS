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
        $components = $this->service->paginate(
            perPage: $request->integer('per_page', 15),
            search: $request->input('search')
        );

        return view('payroll.components.index', compact('components'));
    }

    public function create()
    {
        return view('payroll.components.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
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
            ->route('payroll.components.index')
            ->with('success', 'Salary component created successfully.');
    }

    public function show(SalaryComponent $component)
    {
        $component->load('organization');

        return view('payroll.components.show', compact('component'));
    }

    public function edit(SalaryComponent $component)
    {
        return view('payroll.components.edit', compact('component'));
    }

    public function update(Request $request, SalaryComponent $component)
    {
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
            ->route('payroll.components.index')
            ->with('success', 'Salary component updated successfully.');
    }

    public function destroy(SalaryComponent $component)
    {
        $this->service->delete($component);

        return redirect()
            ->route('payroll.components.index')
            ->with('success', 'Salary component deleted successfully.');
    }
}
