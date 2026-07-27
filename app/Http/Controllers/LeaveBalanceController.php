<?php

namespace App\Http\Controllers;

use App\Models\LeaveBalance;
use App\Models\LeaveType;
use App\Services\LeaveBalanceService;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    public function __construct(private readonly LeaveBalanceService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', LeaveBalance::class);

        $employeeId = $request->input('employee_id');
        $leaveTypes = LeaveType::orderBy('name')->get();

        $balances = $employeeId
            ? $this->service->paginateByEmployee($employeeId, $request->integer('per_page', 15), $request->input('search'))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('leave-balances.index', compact('balances', 'employeeId', 'leaveTypes'));
    }

    public function show(LeaveBalance $leaveBalance)
    {
        $this->authorize('view', $leaveBalance);
        $leaveBalance->load(['employee', 'leaveType']);

        return view('leave-balances.show', compact('leaveBalance'));
    }
}
