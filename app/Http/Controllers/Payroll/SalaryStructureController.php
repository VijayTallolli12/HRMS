<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Services\Payroll\SalaryStructureService;
use Illuminate\Http\Request;

class SalaryStructureController extends Controller
{
    public function __construct(private readonly SalaryStructureService $service) {}

    public function index(Request $request)
    {
        $structures = $this->service->paginate(
            perPage: $request->integer('per_page', 15),
            search: $request->input('search')
        );

        return view('payroll.structures.index', compact('structures'));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();

        return view('payroll.structures.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id|unique:salary_structures,employee_id',
            'organization_id' => 'required|exists:organizations,id',
            'basic_salary' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'pay_frequency' => 'required|in:monthly,weekly,biweekly',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $structure = $this->service->create($data);

        return redirect()
            ->route('payroll.structures.show', $structure)
            ->with('success', 'Salary structure created successfully.');
    }

    public function show(SalaryStructure $structure)
    {
        $structure->load(['employee', 'organization', 'components.salaryComponent']);

        return view('payroll.structures.show', compact('structure'));
    }

    public function edit(SalaryStructure $structure)
    {
        $employees = Employee::orderBy('first_name')->get();

        return view('payroll.structures.edit', compact('structure', 'employees'));
    }

    public function update(Request $request, SalaryStructure $structure)
    {
        $data = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'pay_frequency' => 'required|in:monthly,weekly,biweekly',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $this->service->update($structure, $data);

        return redirect()
            ->route('payroll.structures.show', $structure)
            ->with('success', 'Salary structure updated successfully.');
    }

    public function destroy(SalaryStructure $structure)
    {
        $this->service->delete($structure);

        return redirect()
            ->route('payroll.structures.index')
            ->with('success', 'Salary structure deleted successfully.');
    }
}
