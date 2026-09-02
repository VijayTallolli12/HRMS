<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipItem;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class PayrollRunTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected Tenant $tenant;
    protected Organization $organization;
    protected Branch $branch;
    protected User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();

        $this->tenant = Tenant::factory()->create();
        $this->organization = Organization::factory()->create(['tenant_id' => $this->tenant->id]);
        $this->branch = Branch::factory()->create(['organization_id' => $this->organization->id]);

        $this->superAdmin = User::factory()->forTenant($this->tenant)->create();
        $this->superAdmin->assignRole('super-admin');
    }

    public function test_unauthenticated_user_cannot_view_payroll_runs(): void
    {
        $this->get(route('payroll.runs.index'))->assertRedirect('/login');
    }

    public function test_super_admin_can_view_payroll_runs_list(): void
    {
        PayrollRun::factory()->create([
            'organization_id' => $this->organization->id,
            'branch_id' => $this->branch->id,
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('payroll.runs.index'));
        $response->assertOk();
        $response->assertViewHas('runs');
    }

    public function test_super_admin_can_create_payroll_run(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('payroll.runs.store'), [
                'organization_id' => $this->organization->id,
                'branch_id' => $this->branch->id,
                'period_start' => '2026-08-01',
                'period_end' => '2026-08-31',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payroll_runs', [
            'organization_id' => $this->organization->id,
            'status' => 'draft',
        ]);
    }

    public function test_payslip_and_items_calculations_and_rendering(): void
    {
        $run = PayrollRun::factory()->create([
            'organization_id' => $this->organization->id,
            'period_start' => '2026-08-01',
            'period_end' => '2026-08-31',
            'status' => 'completed',
        ]);

        $employee = Employee::factory()->create([
            'organization_id' => $this->organization->id,
            'branch_id' => $this->branch->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]);

        $basicSalary = 5000.00;
        $allowance = 500.00;
        $deduction = 250.00;

        $grossEarnings = $basicSalary + $allowance; // 5500.00
        $totalDeductions = $deduction;              // 250.00
        $netSalary = $grossEarnings - $totalDeductions; // 5250.00

        $payslip = Payslip::factory()->create([
            'payroll_run_id' => $run->id,
            'employee_id' => $employee->id,
            'basic_salary' => $basicSalary,
            'gross_earnings' => $grossEarnings,
            'total_deductions' => $totalDeductions,
            'net_salary' => $netSalary,
            'status' => 'paid',
        ]);

        PayslipItem::factory()->create([
            'payslip_id' => $payslip->id,
            'component_name' => 'Housing Allowance',
            'type' => 'earning',
            'amount' => $allowance,
        ]);

        PayslipItem::factory()->create([
            'payslip_id' => $payslip->id,
            'component_name' => 'Tax Withholding',
            'type' => 'deduction',
            'amount' => $deduction,
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('payroll.payslips.show', $payslip));

        $response->assertOk();
        $response->assertSee('Jane Doe');
        $response->assertSee('Housing Allowance');
        $response->assertSee('Tax Withholding');

        $this->assertEquals(5250.00, $payslip->net_salary);
        $this->assertEquals($grossEarnings - $totalDeductions, $payslip->net_salary);
    }
}