<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeExport;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Imports\EmployeeImport;
use App\Models\Branch;
use App\Models\CostCenter;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\EmploymentStatus;
use App\Models\EmploymentType;
use App\Models\Organization;
use App\Models\Shift;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function __construct(private readonly EmployeeService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Employee::class);

        $organizations = $this->organizationsFor($request);
        $branches = $this->branchesFor($request);
        $departments = $this->departmentsFor($request);
        $designations = $this->designationsFor($request);

        $organizationId = $request->input('organization_id');
        $filters = $request->only(['status', 'branch_id', 'department_id', 'designation_id']);

        $employees = $this->service->paginateByOrganization(
            organizationId: $organizationId ?? 0,
            perPage: $request->integer('per_page', 15),
            search: $request->input('search'),
            filters: array_filter($filters)
        );

        if (! $organizationId) {
            $employees = $this->service->paginate(
                perPage: $request->integer('per_page', 15),
                search: $request->input('search'),
                filters: array_filter($filters)
            );
        }

        return view('employees.index', compact('employees', 'organizations', 'branches', 'departments', 'designations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Employee::class);

        $organizations = $this->organizationsFor($request);
        $branches = $this->branchesFor($request);
        $departments = $this->departmentsFor($request);
        $designations = $this->designationsFor($request);
        $employmentTypes = EmploymentType::active()->orderBy('name')->get();
        $employeeCategories = EmployeeCategory::active()->orderBy('name')->get();
        $employmentStatuses = EmploymentStatus::active()->orderBy('name')->get();
        $costCenters = CostCenter::orderBy('name')->get();
        $shifts = $this->shiftsFor($request);
        $managers = $this->managersFor($request);
        $nextEmployeeNumber = Employee::nextEmployeeNumber();

        return view('employees.create', compact(
            'organizations',
            'branches',
            'departments',
            'designations',
            'employmentTypes',
            'employeeCategories',
            'employmentStatuses',
            'costCenters',
            'shifts',
            'managers',
            'nextEmployeeNumber',
        ))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $this->authorize('create', Employee::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $employee = $this->service->create($data);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $this->authorize('view', $employee);
        $employee->load([
            'organization', 'branch', 'department', 'designation', 'employmentType',
            'employmentStatus', 'employeeCategory', 'costCenter', 'creator',
        ]);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $this->authorize('update', $employee);
        $request = request();
        $organizations = $this->organizationsFor($request);
        $branches = $this->branchesFor($request);
        $departments = $this->departmentsFor($request);
        $designations = $this->designationsFor($request);
        $employmentTypes = EmploymentType::active()->orderBy('name')->get();
        $employeeCategories = EmployeeCategory::active()->orderBy('name')->get();
        $employmentStatuses = EmploymentStatus::active()->orderBy('name')->get();
        $costCenters = CostCenter::orderBy('name')->get();
        $shifts = $this->shiftsFor($request);
        $managers = $this->managersFor($request)->where('id', '!=', $employee->id);

        return view('employees.edit', compact(
            'employee',
            'organizations',
            'branches',
            'departments',
            'designations',
            'employmentTypes',
            'employeeCategories',
            'employmentStatuses',
            'costCenters',
            'shifts',
            'managers',
        ));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $this->authorize('update', $employee);
        $this->service->update($employee, $request->validated());

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete', $employee);
        $this->service->delete($employee);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    public function deactivate(Employee $employee)
    {
        $this->authorize('update', $employee);

        $this->service->update($employee, ['status' => 'inactive']);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deactivated successfully.');
    }

    public function importPreview(Request $request)
    {
        $this->authorize('create', Employee::class);

        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new EmployeeImport($request->user()->id, ['preview' => true]);
        Excel::import($import, $request->file('import_file'));

        $previewRows = $import->getPreviewRows();
        $skipDuplicates = $request->boolean('skip_duplicates');
        $updateExisting = $request->boolean('update_existing');

        $request->session()->put('import_file_path', $request->file('import_file')->storeAs('temp', 'employee-import-'.time().'.xlsx'));
        $request->session()->put('import_options', compact('skipDuplicates', 'updateExisting'));
        $request->session()->put('import_preview', $previewRows);

        return view('employees.import-preview', compact('previewRows', 'skipDuplicates', 'updateExisting'));
    }

    public function importCommit(Request $request)
    {
        $this->authorize('create', Employee::class);

        $filePath = $request->session()->get('import_file_path');
        $options = $request->session()->get('import_options', []);
        $skipDuplicates = $options['skipDuplicates'] ?? false;
        $updateExisting = $options['updateExisting'] ?? false;

        if (! $filePath || ! \Storage::disk('local')->exists($filePath)) {
            return redirect()
                ->route('employees.index')
                ->with('error', 'Import file not found. Please upload again.');
        }

        try {
            $fullPath = \Storage::disk('local')->path($filePath);
            $import = new EmployeeImport($request->user()->id, [
                'skip_duplicates' => $skipDuplicates,
                'update_existing' => $updateExisting,
            ]);
            Excel::import($import, $fullPath);

            \Storage::disk('local')->delete($filePath);
            $request->session()->forget(['import_file_path', 'import_options', 'import_preview']);

            $summary = $import->getImportSummary();

            return view('employees.import-summary', compact('summary'));
        } catch (\Exception $e) {
            return redirect()
                ->route('employees.index')
                ->with('error', 'Import failed: '.$e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $this->authorize('viewAny', Employee::class);

        return Excel::download(new EmployeeExport($request->user()), 'employees-'.now()->format('Y-m-d').'.xlsx');
    }

    public function downloadTemplate()
    {
        $this->authorize('create', Employee::class);

        return Excel::download(new EmployeeExport(true), 'employee-import-template.xlsx');
    }

    private function organizationsFor(Request $request)
    {
        $query = Organization::orderBy('name');

        if ($request->user()->isBranchAdmin()) {
            $query->where('id', $request->user()->organization_id);
        }

        return $query->get();
    }

    private function branchesFor(Request $request)
    {
        $query = Branch::orderBy('name');

        if ($request->user()->isBranchAdmin()) {
            $query->where('id', $request->user()->branch_id);
        }

        return $query->get();
    }

    private function departmentsFor(Request $request)
    {
        $query = Department::orderBy('name');

        if ($request->user()->isBranchAdmin()) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        return $query->get();
    }

    private function designationsFor(Request $request)
    {
        $query = Designation::orderBy('title');

        if ($request->user()->isBranchAdmin()) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        return $query->get();
    }

    private function shiftsFor(Request $request)
    {
        $query = Shift::active()->orderBy('name');

        if ($request->user()->isBranchAdmin()) {
            $query->where('organization_id', $request->user()->organization_id);
        }

        return $query->get();
    }

    private function managersFor(Request $request)
    {
        $query = Employee::active()->orderBy('first_name')->orderBy('last_name');

        if ($request->user()->isBranchAdmin()) {
            $query->where('branch_id', $request->user()->branch_id);
        }

        return $query->get();
    }
}
