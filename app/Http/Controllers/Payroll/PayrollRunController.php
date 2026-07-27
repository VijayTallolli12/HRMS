<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Services\Payroll\PayrollRunService;
use Illuminate\Http\Request;

class PayrollRunController extends Controller
{
    public function __construct(private readonly PayrollRunService $service) {}

    public function index(Request $request)
    {
        $this->authorize('view-payroll-run');

        $runs = $this->service->paginate(
            perPage: $request->integer('per_page', 15),
            search: $request->input('search')
        );

        return view('payroll.runs.index', compact('runs'));
    }

    public function create()
    {
        $this->authorize('create-payroll-run');

        $employees = Employee::orderBy('first_name')->get();

        return view('payroll.runs.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-payroll-run');
        $data = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'branch_id' => 'nullable|exists:branches,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $data['status'] = 'draft';
        $data['total_gross'] = 0;
        $data['total_deductions'] = 0;
        $data['total_net'] = 0;

        $run = $this->service->create($data);

        return redirect()
            ->route('payroll.runs.show', $run)
            ->with('success', 'Payroll run created successfully.');
    }

    public function show(PayrollRun $run)
    {
        $this->authorize('view-payroll-run');

        $run->load(['organization', 'branch', 'processor', 'payslips.employee']);

        return view('payroll.runs.show', compact('run'));
    }

    public function destroy(PayrollRun $run)
    {
        $this->authorize('delete-payroll-run');
        $this->service->delete($run);

        return redirect()
            ->route('payroll.runs.index')
            ->with('success', 'Payroll run deleted successfully.');
    }

    public function showPayslip(Payslip $payslip)
    {
        $payslip->load(['employee', 'employee.department', 'employee.designation', 'items.salaryComponent', 'payrollRun']);

        return view('payroll.payslips.show', ['run' => $payslip->payrollRun, 'payslip' => $payslip]);
    }
}
