<?php

namespace Database\Seeders;

use App\Models\ApplicationSetting;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\CostCenter;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\MissingPunch;
use App\Models\EmployeeCategory;
use App\Models\EmployeeSalaryComponent;
use App\Models\EmploymentStatus;
use App\Models\EmploymentType;
use App\Models\Holiday;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Models\Organization;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipItem;
use App\Models\ReportingHierarchy;
use App\Models\SalaryComponent;
use App\Models\SalaryStructure;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WeekendPolicy;
use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    private $admin;

    private $branchAdmin;

    private array $firstNames = [
        'James', 'Mary', 'Robert', 'Patricia', 'John', 'Jennifer', 'Michael', 'Linda',
        'David', 'Elizabeth', 'William', 'Barbara', 'Richard', 'Susan', 'Joseph', 'Jessica',
        'Thomas', 'Sarah', 'Christopher', 'Karen', 'Charles', 'Lisa', 'Daniel', 'Nancy',
        'Matthew', 'Betty', 'Anthony', 'Margaret', 'Mark', 'Sandra', 'Donald', 'Ashley',
        'Steven', 'Kimberly', 'Paul', 'Emily', 'Andrew', 'Donna', 'Joshua', 'Michelle',
        'Kenneth', 'Dorothy', 'Kevin', 'Carol', 'Brian', 'Amanda', 'George', 'Melissa',
        'Timothy', 'Deborah', 'Ronald', 'Stephanie', 'Edward', 'Rebecca', 'Jason', 'Sharon',
        'Jeffrey', 'Laura', 'Ryan', 'Cynthia', 'Jacob', 'Kathleen', 'Gary', 'Amy',
        'Nicholas', 'Angela', 'Eric', 'Shirley', 'Jonathan', 'Anna', 'Stephen', 'Brenda',
        'Larry', 'Pamela', 'Justin', 'Emma', 'Scott', 'Nicole', 'Brandon', 'Helen',
        'Benjamin', 'Samantha', 'Samuel', 'Katherine', 'Raymond', 'Christine', 'Gregory', 'Debra',
        'Frank', 'Rachel', 'Alexander', 'Carolyn', 'Patrick', 'Janet', 'Jack', 'Catherine',
    ];

    private array $lastNames = [
        'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis',
        'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson',
        'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee', 'Perez', 'Thompson',
        'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson', 'Walker',
        'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 'Flores',
        'Green', 'Adams', 'Nelson', 'Baker', 'Hall', 'Rivera', 'Campbell', 'Mitchell',
        'Carter', 'Roberts', 'Gomez', 'Phillips', 'Evans', 'Turner', 'Diaz', 'Parker',
        'Cruz', 'Edwards', 'Collins', 'Reyes', 'Stewart', 'Morris', 'Morales', 'Murphy',
    ];

    private array $departmentDesignations = [
        'Human Resources' => [
            ['title' => 'HR Director', 'level' => 'L1'],
            ['title' => 'HR Manager', 'level' => 'L2'],
            ['title' => 'HR Coordinator', 'level' => 'L3'],
            ['title' => 'Recruiter', 'level' => 'L4'],
            ['title' => 'HR Assistant', 'level' => 'L5'],
        ],
        'Engineering' => [
            ['title' => 'VP Engineering', 'level' => 'L1'],
            ['title' => 'Engineering Manager', 'level' => 'L2'],
            ['title' => 'Senior Engineer', 'level' => 'L3'],
            ['title' => 'Software Engineer', 'level' => 'L4'],
            ['title' => 'Junior Developer', 'level' => 'L5'],
        ],
        'Marketing' => [
            ['title' => 'Marketing Director', 'level' => 'L1'],
            ['title' => 'Marketing Manager', 'level' => 'L2'],
            ['title' => 'Content Strategist', 'level' => 'L3'],
            ['title' => 'Designer', 'level' => 'L4'],
            ['title' => 'Marketing Coordinator', 'level' => 'L5'],
        ],
        'Finance' => [
            ['title' => 'CFO', 'level' => 'L1'],
            ['title' => 'Finance Manager', 'level' => 'L2'],
            ['title' => 'Financial Analyst', 'level' => 'L3'],
            ['title' => 'Accountant', 'level' => 'L4'],
            ['title' => 'Accounts Assistant', 'level' => 'L5'],
        ],
        'Operations' => [
            ['title' => 'Operations Director', 'level' => 'L1'],
            ['title' => 'Operations Manager', 'level' => 'L2'],
            ['title' => 'Logistics Coordinator', 'level' => 'L3'],
            ['title' => 'Business Analyst', 'level' => 'L4'],
            ['title' => 'Operations Assistant', 'level' => 'L5'],
        ],
        'Sales' => [
            ['title' => 'Sales Director', 'level' => 'L1'],
            ['title' => 'Sales Manager', 'level' => 'L2'],
            ['title' => 'Senior Sales Executive', 'level' => 'L3'],
            ['title' => 'Sales Executive', 'level' => 'L4'],
            ['title' => 'Sales Representative', 'level' => 'L5'],
        ],
        'Customer Support' => [
            ['title' => 'Support Director', 'level' => 'L1'],
            ['title' => 'Support Manager', 'level' => 'L2'],
            ['title' => 'Senior Support Agent', 'level' => 'L3'],
            ['title' => 'Support Agent', 'level' => 'L4'],
            ['title' => 'Junior Support Agent', 'level' => 'L5'],
        ],
        'IT' => [
            ['title' => 'IT Director', 'level' => 'L1'],
            ['title' => 'IT Manager', 'level' => 'L2'],
            ['title' => 'Senior Systems Admin', 'level' => 'L3'],
            ['title' => 'Systems Administrator', 'level' => 'L4'],
            ['title' => 'IT Support Specialist', 'level' => 'L5'],
        ],
    ];

    public function run(): void
    {
        $this->admin = User::where('email', 'admin@example.com')->first();
        $this->branchAdmin = User::where('email', 'branch@example.com')->first();

        $tenant = Tenant::firstOrCreate(['name' => 'Default']);

        $orgs = $this->createOrganizations($tenant);
        $allBranches = $orgs->flatMap(fn ($o) => Branch::where('organization_id', $o->id)->get());
        $allDepts = $orgs->flatMap(fn ($o) => Department::where('organization_id', $o->id)->get());
        $allEmployees = Employee::count() > 0
            ? Employee::all()
            : $this->createEmployees($orgs, $allBranches, $allDepts);

        $this->createShiftsAndSchedules($orgs);
        $this->createHolidays($orgs);
        $this->createCostCenters($orgs, $allDepts);
        if (ReportingHierarchy::count() === 0) {
            $this->createReportingHierarchies($allEmployees);
        }
        $this->createLeaveTypesAndBalances($orgs, $allEmployees);
        if (Attendance::count() === 0) {
            $this->createAttendanceRecords($allEmployees);
        }
        if (Leave::count() === 0) {
            $this->createLeaveRecords($allEmployees);
        }
        $this->createSalaryStructuresAndPayroll($orgs, $allEmployees);
        if (MissingPunch::count() === 0) {
            $this->createMissingPunches($allEmployees);
        }
        $this->createApplicationSettings();

        $this->command->info('Demo data seeded successfully!');
    }

    private function createOrganizations(Tenant $tenant): Collection
    {
        $orgData = [
            ['name' => 'Acme Corporation', 'legal_name' => 'Acme Corporation Ltd.', 'tax_id' => 'TAX-ACME-001'],
            ['name' => 'TechVentures Inc.', 'legal_name' => 'TechVentures Incorporated', 'tax_id' => 'TAX-TV-002'],
        ];

        $orgs = collect();
        foreach ($orgData as $data) {
            $org = Organization::firstOrCreate(
                ['name' => $data['name']],
                [
                    'tenant_id' => $tenant->id,
                    'legal_name' => $data['legal_name'],
                    'tax_id' => $data['tax_id'],
                    'address' => [
                        'street' => fake()->streetAddress(),
                        'city' => fake()->city(),
                        'state' => fake()->stateAbbr(),
                        'country' => 'USA',
                        'zip' => fake()->postcode(),
                    ],
                    'status' => 'active',
                    'created_by' => $this->admin?->id,
                ]
            );
            $orgs->push($org);

            $branchesData = match ($data['name']) {
                'Acme Corporation' => [
                    ['name' => 'New York HQ', 'city' => 'New York', 'state' => 'NY'],
                    ['name' => 'San Francisco Office', 'city' => 'San Francisco', 'state' => 'CA'],
                    ['name' => 'Chicago Office', 'city' => 'Chicago', 'state' => 'IL'],
                    ['name' => 'Austin Office', 'city' => 'Austin', 'state' => 'TX'],
                ],
                default => [
                    ['name' => 'Main Office', 'city' => 'Seattle', 'state' => 'WA'],
                    ['name' => 'Remote Hub', 'city' => 'Denver', 'state' => 'CO'],
                ],
            };

            foreach ($branchesData as $bd) {
                Branch::firstOrCreate(
                    ['name' => $bd['name'], 'organization_id' => $org->id],
                    [
                        'address' => [
                            'street' => fake()->streetAddress(),
                            'city' => $bd['city'],
                            'state' => $bd['state'],
                            'country' => 'USA',
                            'zip' => fake()->postcode(),
                        ],
                        'phone' => fake()->phoneNumber(),
                        'status' => 'active',
                        'created_by' => $this->admin?->id,
                    ]
                );
            }

            $this->createDepartmentsAndDesignations($org);
        }

        return $orgs;
    }

    private function createDepartmentsAndDesignations(Organization $org): void
    {
        foreach ($this->departmentDesignations as $deptName => $designations) {
            $dept = Department::firstOrCreate(
                ['name' => $deptName, 'organization_id' => $org->id],
                [
                    'description' => "Department of {$deptName}",
                    'status' => 'active',
                    'created_by' => $this->admin?->id,
                ]
            );

            foreach ($designations as $d) {
                Designation::firstOrCreate(
                    ['title' => $d['title'], 'organization_id' => $org->id],
                    [
                        'department_id' => $dept->id,
                        'level' => $d['level'],
                        'description' => "Role: {$d['title']}",
                        'status' => 'active',
                        'created_by' => $this->admin?->id,
                    ]
                );
            }
        }
    }

    private function createEmployees(
        Collection $orgs,
        Collection $allBranches,
        Collection $allDepts
    ): Collection {
        $employees = collect();
        $empCounter = 1;

        $employmentTypes = EmploymentType::all();
        $categories = EmployeeCategory::all();
        $statuses = EmploymentStatus::all();

        foreach ($orgs as $org) {
            $branches = Branch::where('organization_id', $org->id)->get();
            $departments = Department::where('organization_id', $org->id)->get();

            $employeesPerOrg = $org->name === 'Acme Corporation' ? 50 : 20;

            for ($i = 0; $i < $employeesPerOrg; $i++) {
                $dept = $departments->random();
                $desigs = Designation::where('department_id', $dept->id)
                    ->where('organization_id', $org->id)
                    ->get();
                $desig = $desigs->random();
                $branch = $branches->random();

                $hiredDate = fake()->dateTimeBetween('-4 years', '-1 month');
                $empStatus = fake()->randomElement(['active', 'active', 'active', 'active', 'on-leave']);

                $employee = Employee::create([
                    'organization_id' => $org->id,
                    'branch_id' => $branch->id,
                    'department_id' => $dept->id,
                    'designation_id' => $desig->id,
                    'employment_type_id' => $employmentTypes->random()->id,
                    'employee_category_id' => $categories->random()->id,
                    'employment_status_id' => $statuses->where('name', 'Active')->first()?->id ?? $statuses->random()->id,
                    'first_name' => $this->firstNames[array_rand($this->firstNames)],
                    'last_name' => $this->lastNames[array_rand($this->lastNames)],
                    'employee_number' => 'EMP-'.str_pad($empCounter, 5, '0', STR_PAD_LEFT),
                    'email' => strtolower($this->firstNames[array_rand($this->firstNames)].'.'.$this->lastNames[array_rand($this->lastNames)].'@'.str_replace(' ', '', strtolower($org->name)).'.com'),
                    'phone' => fake()->phoneNumber(),
                    'hired_at' => $hiredDate,
                    'status' => $empStatus,
                    'meta' => [],
                    'created_by' => $this->admin?->id,
                ]);

                $employees->push($employee);
                $empCounter++;
            }
        }

        return $employees;
    }

    private function createShiftsAndSchedules(Collection $orgs): void
    {
        foreach ($orgs as $org) {
            $shiftData = [
                ['name' => 'Morning Shift', 'start_time' => '06:00', 'end_time' => '14:00', 'break_minutes' => 60],
                ['name' => 'Day Shift', 'start_time' => '09:00', 'end_time' => '18:00', 'break_minutes' => 60],
                ['name' => 'Evening Shift', 'start_time' => '14:00', 'end_time' => '22:00', 'break_minutes' => 60],
                ['name' => 'Night Shift', 'start_time' => '22:00', 'end_time' => '06:00', 'break_minutes' => 60],
            ];

            foreach ($shiftData as $sd) {
                Shift::firstOrCreate(
                    ['name' => $sd['name'], 'organization_id' => $org->id],
                    [
                        'start_time' => $sd['start_time'],
                        'end_time' => $sd['end_time'],
                        'break_minutes' => $sd['break_minutes'],
                        'description' => "{$sd['name']} for {$org->name}",
                        'is_active' => true,
                        'created_by' => $this->admin?->id,
                    ]
                );
            }

            WorkSchedule::firstOrCreate(
                ['name' => 'Standard Work Week', 'organization_id' => $org->id],
                [
                    'working_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                    'hours_per_day' => 8.00,
                    'break_minutes' => 60,
                    'description' => 'Standard Monday-Friday work week',
                    'is_active' => true,
                    'created_by' => $this->admin?->id,
                ]
            );

            WeekendPolicy::firstOrCreate(
                ['name' => 'Standard Weekend', 'organization_id' => $org->id],
                [
                    'weekend_days' => ['saturday', 'sunday'],
                    'description' => 'Standard Saturday-Sunday weekend',
                    'is_active' => true,
                    'created_by' => $this->admin?->id,
                ]
            );

            $shift = Shift::where('name', 'Day Shift')->where('organization_id', $org->id)->first();
            $empIds = Employee::where('organization_id', $org->id)->pluck('id')->toArray();
            foreach (array_slice($empIds, 0, min(20, count($empIds))) as $empId) {
                ShiftAssignment::firstOrCreate(
                    ['employee_id' => $empId, 'shift_id' => $shift?->id],
                    [
                        'effective_from' => now()->subMonths(6)->format('Y-m-d'),
                        'effective_to' => null,
                        'is_active' => true,
                    ]
                );
            }
        }
    }

    private function createHolidays(Collection $orgs): void
    {
        $holidays = [
            ['name' => "New Year's Day", 'month' => 1, 'day' => 1, 'type' => 'public'],
            ['name' => 'Martin Luther King Jr. Day', 'month' => 1, 'day' => 20, 'type' => 'national'],
            ['name' => "Presidents' Day", 'month' => 2, 'day' => 17, 'type' => 'national'],
            ['name' => 'Memorial Day', 'month' => 5, 'day' => 26, 'type' => 'national'],
            ['name' => 'Independence Day', 'month' => 7, 'day' => 4, 'type' => 'public'],
            ['name' => 'Labor Day', 'month' => 9, 'day' => 1, 'type' => 'national'],
            ['name' => 'Thanksgiving Day', 'month' => 11, 'day' => 27, 'type' => 'public'],
            ['name' => 'Day After Thanksgiving', 'month' => 11, 'day' => 28, 'type' => 'optional'],
            ['name' => 'Christmas Eve', 'month' => 12, 'day' => 24, 'type' => 'optional'],
            ['name' => 'Christmas Day', 'month' => 12, 'day' => 25, 'type' => 'public'],
            ['name' => "New Year's Eve", 'month' => 12, 'day' => 31, 'type' => 'optional'],
        ];

        foreach ($orgs as $org) {
            foreach ($holidays as $h) {
                $year = (int) date('Y');
                $date = sprintf('%d-%02d-%02d', $year, $h['month'], $h['day']);
                if (strtotime($date) < time()) {
                    $date = sprintf('%d-%02d-%02d', $year + 1, $h['month'], $h['day']);
                }
                Holiday::firstOrCreate(
                    ['name' => $h['name'], 'organization_id' => $org->id],
                    [
                        'date' => $date,
                        'type' => $h['type'],
                        'description' => "{$h['name']} holiday",
                        'is_active' => true,
                    ]
                );
            }
        }
    }

    private function createCostCenters(Collection $orgs, Collection $allDepts): void
    {
        foreach ($orgs as $org) {
            $departments = Department::where('organization_id', $org->id)->get();
            foreach ($departments as $idx => $dept) {
                CostCenter::firstOrCreate(
                    ['code' => 'CC-'.str_pad($idx + 1, 3, '0', STR_PAD_LEFT), 'organization_id' => $org->id],
                    [
                        'name' => "{$dept->name} Cost Center",
                        'department_id' => $dept->id,
                        'description' => "Cost center for {$dept->name}",
                        'budget' => fake()->randomFloat(2, 50000, 500000),
                        'is_active' => true,
                        'created_by' => $this->admin?->id,
                    ]
                );
            }
        }
    }

    private function createReportingHierarchies(Collection $allEmployees): void
    {
        $grouped = $allEmployees->groupBy(fn ($e) => $e->organization_id.'-'.$e->branch_id);

        foreach ($grouped as $group) {
            $sorted = $group->sortBy('id')->values();
            if ($sorted->count() < 2) {
                continue;
            }

            $director = $sorted->first();

            for ($i = 1; $i < $sorted->count(); $i++) {
                $managerIdx = max(0, intdiv($i, 5));
                ReportingHierarchy::firstOrCreate(
                    [
                        'employee_id' => $sorted[$i]->id,
                        'manager_id' => $sorted[$managerIdx]->id,
                        'reporting_type' => 'direct',
                    ],
                    [
                        'effective_from' => $director['hired_at'] ?? now()->subYear(),
                        'effective_to' => null,
                        'is_active' => true,
                        'created_by' => $this->admin?->id,
                    ]
                );
            }
        }
    }

    private function createLeaveTypesAndBalances(
        Collection $orgs,
        Collection $allEmployees
    ): void {
        foreach ($orgs as $org) {
            $leaveTypesData = [
                ['name' => 'Annual Leave', 'days_per_year' => 20],
                ['name' => 'Sick Leave', 'days_per_year' => 12],
                ['name' => 'Personal Leave', 'days_per_year' => 5],
                ['name' => 'Maternity Leave', 'days_per_year' => 90],
                ['name' => 'Paternity Leave', 'days_per_year' => 14],
                ['name' => 'Bereavement Leave', 'days_per_year' => 5],
                ['name' => 'Unpaid Leave', 'days_per_year' => 0],
            ];

            foreach ($leaveTypesData as $lt) {
                LeaveType::firstOrCreate(
                    ['name' => $lt['name'], 'organization_id' => $org->id],
                    [
                        'days_per_year' => $lt['days_per_year'],
                        'is_active' => true,
                    ]
                );
            }
        }

        $orgEmployees = $allEmployees->groupBy('organization_id');
        foreach ($orgEmployees as $orgId => $employees) {
            $leaveTypes = LeaveType::where('organization_id', $orgId)->get();
            foreach ($employees as $emp) {
                foreach ($leaveTypes as $lt) {
                    if ($lt->days_per_year === 0) {
                        continue;
                    }
                    $entitled = (float) $lt->days_per_year;
                    $taken = fake()->randomFloat(1, 0, min($entitled, 10));
                    $pending = fake()->randomFloat(1, 0, max(0, $entitled - $taken - 2));

                    LeaveBalance::firstOrCreate(
                        ['employee_id' => $emp->id, 'leave_type_id' => $lt->id, 'year' => (int) date('Y')],
                        [
                            'entitled' => $entitled,
                            'taken' => round($taken, 1),
                            'pending' => round($pending, 1),
                            'remaining' => round($entitled - $taken - $pending, 1),
                        ]
                    );
                }
            }
        }
    }

    private function createAttendanceRecords(Collection $allEmployees): void
    {
        $today = now()->copy();
        $start = $today->copy()->subDays(30);

        $records = [];
        foreach ($allEmployees as $emp) {
            $current = $start->copy();
            while ($current->lte($today)) {
                if ($current->isWeekend()) {
                    $current->addDay();

                    continue;
                }

                $rand = mt_rand(1, 100);
                $status = match (true) {
                    $rand <= 70 => 'present',
                    $rand <= 82 => 'late',
                    $rand <= 88 => 'half-day',
                    $rand <= 93 => 'absent',
                    default => 'present',
                };

                $clockIn = match ($status) {
                    'present' => sprintf('%02d:%02d:00', mt_rand(8, 9), mt_rand(0, 59)),
                    'late' => sprintf('%02d:%02d:00', mt_rand(9, 10), mt_rand(0, 59)),
                    'half-day' => sprintf('%02d:%02d:00', mt_rand(9, 10), mt_rand(0, 59)),
                    default => null,
                };

                $clockOut = match ($status) {
                    'present' => sprintf('%02d:%02d:00', mt_rand(17, 18), mt_rand(0, 59)),
                    'late' => sprintf('%02d:%02d:00', mt_rand(17, 19), mt_rand(0, 59)),
                    'half-day' => sprintf('%02d:%02d:00', mt_rand(13, 14), mt_rand(0, 59)),
                    default => null,
                };

                $hoursWorked = match ($status) {
                    'present' => fake()->randomFloat(2, 7.5, 9.5),
                    'late' => fake()->randomFloat(2, 6.0, 8.5),
                    'half-day' => fake()->randomFloat(2, 3.5, 5.0),
                    default => 0,
                };

                $lateMinutes = match ($status) {
                    'late' => fake()->numberBetween(10, 90),
                    default => 0,
                };

                $overtimeHours = ($status === 'present' && mt_rand(1, 100) <= 15)
                    ? fake()->randomFloat(2, 1, 4) : 0;

                $records[] = [
                    'organization_id' => $emp->organization_id,
                    'employee_id' => $emp->id,
                    'date' => $current->format('Y-m-d'),
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'status' => $status,
                    'hours_worked' => $hoursWorked,
                    'overtime_hours' => $overtimeHours,
                    'late_minutes' => $lateMinutes,
                    'early_leave_minutes' => $status === 'half-day' ? fake()->numberBetween(60, 180) : 0,
                    'notes' => null,
                    'created_by' => $this->admin?->id,
                    'source' => 'manual',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $current->addDay();
            }
        }

        $chunks = array_chunk($records, 500);
        foreach ($chunks as $chunk) {
            DB::table('attendances')->insert($chunk);
        }
    }

    private function createLeaveRecords(Collection $allEmployees): void
    {
        $leaveTypes = ['annual', 'sick', 'personal', 'unpaid', 'maternity', 'paternity'];
        $records = [];
        $empSample = $allEmployees->random(min(40, $allEmployees->count()));

        foreach ($empSample as $emp) {
            $numLeaves = mt_rand(1, 3);
            for ($i = 0; $i < $numLeaves; $i++) {
                $status = fake()->randomElement(['approved', 'approved', 'approved', 'pending', 'rejected']);
                $startDate = fake()->dateTimeBetween('-60 days', '+30 days');
                $days = fake()->numberBetween(1, 5);
                $endDate = (clone $startDate)->modify('+'.($days - 1).' days');

                $records[] = [
                    'employee_id' => $emp->id,
                    'organization_id' => $emp->organization_id,
                    'leave_type' => fake()->randomElement($leaveTypes),
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'days' => $days,
                    'reason' => fake()->sentence(),
                    'status' => $status,
                    'rejection_reason' => $status === 'rejected' ? 'Schedule conflict' : null,
                    'approved_by' => $status === 'approved' ? $this->admin?->id : null,
                    'created_by' => $this->admin?->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $chunks = array_chunk($records, 500);
        foreach ($chunks as $chunk) {
            DB::table('leaves')->insert($chunk);
        }
    }

    private function createSalaryStructuresAndPayroll(
        Collection $orgs,
        Collection $allEmployees
    ): void {
        $salaryComponents = [];
        foreach ($orgs as $org) {
            $componentsData = [
                ['name' => 'Basic Salary', 'code' => 'BASIC', 'type' => 'earning', 'calculation_type' => 'fixed', 'default_value' => 5000],
                ['name' => 'House Rent Allowance', 'code' => 'HRA', 'type' => 'earning', 'calculation_type' => 'percentage', 'default_value' => 40],
                ['name' => 'Dearness Allowance', 'code' => 'DA', 'type' => 'earning', 'calculation_type' => 'percentage', 'default_value' => 10],
                ['name' => 'Transport Allowance', 'code' => 'TA', 'type' => 'earning', 'calculation_type' => 'fixed', 'default_value' => 800],
                ['name' => 'Medical Allowance', 'code' => 'MED', 'type' => 'earning', 'calculation_type' => 'fixed', 'default_value' => 500],
                ['name' => 'Provident Fund', 'code' => 'PF', 'type' => 'deduction', 'calculation_type' => 'percentage', 'default_value' => 12],
                ['name' => 'ESI', 'code' => 'ESI', 'type' => 'deduction', 'calculation_type' => 'percentage', 'default_value' => 0.75],
                ['name' => 'Professional Tax', 'code' => 'PTAX', 'type' => 'deduction', 'calculation_type' => 'fixed', 'default_value' => 200],
            ];

            foreach ($componentsData as $cd) {
                $comp = SalaryComponent::firstOrCreate(
                    ['code' => $cd['code'], 'organization_id' => $org->id],
                    [
                        'name' => $cd['name'],
                        'type' => $cd['type'],
                        'calculation_type' => $cd['calculation_type'],
                        'default_value' => $cd['default_value'],
                        'is_active' => true,
                    ]
                );
                $salaryComponents[$org->id][] = $comp;
            }
        }

        $orgEmployees = $allEmployees->groupBy('organization_id');
        foreach ($orgEmployees as $orgId => $employees) {
            $components = $salaryComponents[$orgId] ?? [];

            foreach ($employees as $emp) {
                $basicSalary = match ($emp->status) {
                    'active' => fake()->randomFloat(2, 4000, 12000),
                    default => fake()->randomFloat(2, 2000, 5000),
                };

                $structure = SalaryStructure::firstOrCreate(
                    ['employee_id' => $emp->id],
                    [
                        'organization_id' => $orgId,
                        'basic_salary' => $basicSalary,
                        'currency' => 'INR',
                        'pay_frequency' => 'monthly',
                        'effective_from' => $emp->hired_at?->format('Y-m-d') ?? now()->subYear()->format('Y-m-d'),
                        'effective_to' => null,
                        'is_active' => true,
                    ]
                );

                $basicComp = collect($components)->firstWhere('code', 'BASIC');
                if ($basicComp) {
                    EmployeeSalaryComponent::firstOrCreate(
                        ['employee_id' => $emp->id, 'salary_component_id' => $basicComp->id],
                        ['amount' => $basicSalary, 'percentage' => 0, 'is_active' => true]
                    );
                }

                foreach ($components as $comp) {
                    if ($comp->code === 'BASIC') {
                        continue;
                    }
                    $amount = $comp->calculation_type === 'percentage'
                        ? 0
                        : $comp->default_value;
                    $pct = $comp->calculation_type === 'percentage'
                        ? $comp->default_value
                        : 0;

                    EmployeeSalaryComponent::firstOrCreate(
                        ['employee_id' => $emp->id, 'salary_component_id' => $comp->id],
                        ['amount' => $amount, 'percentage' => $pct, 'is_active' => true]
                    );
                }
            }

            for ($monthsAgo = 2; $monthsAgo >= 0; $monthsAgo--) {
                $periodStart = now()->subMonths($monthsAgo)->startOfMonth();
                $periodEnd = now()->subMonths($monthsAgo)->endOfMonth();

                $run = PayrollRun::firstOrCreate(
                    [
                        'organization_id' => $orgId,
                        'period_start' => $periodStart->format('Y-m-d'),
                        'period_end' => $periodEnd->format('Y-m-d'),
                    ],
                    [
                        'branch_id' => Branch::where('organization_id', $orgId)->first()?->id,
                        'status' => $monthsAgo === 0 ? 'completed' : 'paid',
                        'total_gross' => 0,
                        'total_deductions' => 0,
                        'total_net' => 0,
                        'processed_by' => $this->admin?->id,
                        'processed_at' => $periodEnd->copy()->addDays(3),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                $totalGross = 0;
                $totalDeductions = 0;

                foreach ($employees as $emp) {
                    $salaryStructure = SalaryStructure::where('employee_id', $emp->id)->first();
                    $basic = $salaryStructure?->basic_salary ?? 5000;
                    $hra = $basic * 0.40;
                    $da = $basic * 0.10;
                    $ta = 800;
                    $med = 500;
                    $gross = $basic + $hra + $da + $ta + $med;
                    $pf = $basic * 0.12;
                    $esi = $gross * 0.0075;
                    $ptax = 200;
                    $itax = $gross * 0.10;
                    $totalDed = $pf + $esi + $ptax + $itax;
                    $net = $gross - $totalDed;

                    $payslip = Payslip::firstOrCreate(
                        ['payroll_run_id' => $run->id, 'employee_id' => $emp->id],
                        [
                            'basic_salary' => $basic,
                            'gross_earnings' => $gross,
                            'total_deductions' => $totalDed,
                            'net_salary' => $net,
                            'status' => $monthsAgo === 0 ? 'processed' : 'paid',
                            'paid_at' => $monthsAgo === 0 ? null : $periodEnd->copy()->addDays(5),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    $items = [
                        ['component_name' => 'Basic Salary', 'type' => 'earning', 'amount' => $basic],
                        ['component_name' => 'House Rent Allowance', 'type' => 'earning', 'amount' => $hra],
                        ['component_name' => 'Dearness Allowance', 'type' => 'earning', 'amount' => $da],
                        ['component_name' => 'Transport Allowance', 'type' => 'earning', 'amount' => $ta],
                        ['component_name' => 'Medical Allowance', 'type' => 'earning', 'amount' => $med],
                        ['component_name' => 'Provident Fund', 'type' => 'deduction', 'amount' => $pf],
                        ['component_name' => 'ESI', 'type' => 'deduction', 'amount' => round($esi, 2)],
                        ['component_name' => 'Professional Tax', 'type' => 'deduction', 'amount' => $ptax],
                        ['component_name' => 'Income Tax', 'type' => 'deduction', 'amount' => round($itax, 2)],
                    ];

                    foreach ($items as $item) {
                        PayslipItem::firstOrCreate(
                            [
                                'payslip_id' => $payslip->id,
                                'component_name' => $item['component_name'],
                            ],
                            [
                                'salary_component_id' => null,
                                'type' => $item['type'],
                                'amount' => $item['amount'],
                            ]
                        );
                    }

                    $totalGross += $gross;
                    $totalDeductions += $totalDed;
                }

                $run->update([
                    'total_gross' => $totalGross,
                    'total_deductions' => $totalDeductions,
                    'total_net' => $totalGross - $totalDeductions,
                ]);
            }
        }
    }

    private function createMissingPunches(Collection $allEmployees): void
    {
        $empSample = $allEmployees->random(min(15, $allEmployees->count()));
        $records = [];

        foreach ($empSample as $emp) {
            $date = now()->subDays(mt_rand(1, 14));
            if ($date->isWeekend()) {
                continue;
            }

            $punchType = fake()->randomElement(['clock_in', 'clock_out']);
            $resolved = fake()->boolean(30);

            $records[] = [
                'employee_id' => $emp->id,
                'date' => $date->format('Y-m-d'),
                'punch_type' => $punchType,
                'original_value' => null,
                'status' => $resolved ? 'resolved' : 'pending',
                'detected_by' => fake()->randomElement(['system', 'manager']),
                'resolved_by' => $resolved ? $this->admin?->id : null,
                'resolution_notes' => $resolved ? 'Employee confirmed working from home' : null,
                'resolved_at' => $resolved ? $date->copy()->addDays(1) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($records) {
            $chunks = array_chunk($records, 500);
            foreach ($chunks as $chunk) {
                DB::table('missing_punches')->insert($chunk);
            }
        }
    }

    private function createApplicationSettings(): void
    {
        $settings = [
            ['key' => 'company_name', 'value' => ['raw' => 'Blueline Foods India Pvt. Ltd.'], 'group' => 'general'],
            ['key' => 'address', 'value' => ['raw' => '123 Business Ave, Suite 100, New York, NY 10001'], 'group' => 'general'],
            ['key' => 'phone', 'value' => ['raw' => '+1 (555) 123-4567'], 'group' => 'general'],
            ['key' => 'email', 'value' => ['raw' => 'hr@acme.com'], 'group' => 'general'],
            ['key' => 'website', 'value' => ['raw' => 'https://acme.com'], 'group' => 'general'],
            ['key' => 'app_title', 'value' => ['raw' => 'Blueline HRMS'], 'group' => 'branding'],
            ['key' => 'primary_color', 'value' => ['raw' => '#4f46e5'], 'group' => 'branding'],
            ['key' => 'smtp_host', 'value' => ['raw' => 'smtp.acme.com'], 'group' => 'email'],
            ['key' => 'smtp_port', 'value' => ['raw' => '587'], 'group' => 'email'],
            ['key' => 'from_address', 'value' => ['raw' => 'noreply@acme.com'], 'group' => 'email'],
            ['key' => 'from_name', 'value' => ['raw' => 'Acme HRMS'], 'group' => 'email'],
            ['key' => 'encryption', 'value' => ['raw' => 'tls'], 'group' => 'email'],
            ['key' => 'date_format', 'value' => ['raw' => 'Y-m-d'], 'group' => 'system'],
            ['key' => 'timezone', 'value' => ['raw' => 'UTC'], 'group' => 'system'],
            ['key' => 'currency', 'value' => ['raw' => 'INR'], 'group' => 'system'],
        ];

        foreach ($settings as $setting) {
            ApplicationSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
