<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $service) {}

    public function __invoke(Request $request)
    {
        $user = $request->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $branchId = $user->branch_id;

        $data = $this->service->getDashboardData($isSuperAdmin, $branchId);

        return view('dashboard', array_merge($data, [
            'isSuperAdmin' => $isSuperAdmin,
            'user' => $user,
        ]));
    }
}
