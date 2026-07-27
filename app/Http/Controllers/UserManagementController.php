<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserManagementRequest;
use App\Http\Requests\UpdateUserManagementRequest;
use App\Models\Branch;
use App\Models\Organization;
use App\Models\User;
use App\Services\UserManagementService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function __construct(private readonly UserManagementService $service) {}

    public function index(Request $request)
    {
        $this->authorize('view-user-management');

        $organizations = Organization::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        $organizationId = $request->input('organization_id');
        $filters = $request->only(['status', 'branch_id']);

        $users = $this->service->paginate(
            perPage: $request->integer('per_page', 15),
            search: $request->input('search'),
            organizationId: $organizationId,
            filters: array_filter($filters)
        );

        return view('user-management.index', compact('users', 'organizations', 'branches', 'roles', 'organizationId'));
    }

    public function create()
    {
        $this->authorize('create-user-management');

        $organizations = Organization::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('user-management.create', compact('organizations', 'branches', 'roles'));
    }

    public function store(StoreUserManagementRequest $request)
    {
        $this->authorize('create-user-management');

        $user = $this->service->create($request->validated());

        return redirect()
            ->route('user-management.show', $user)
            ->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        $this->authorize('view-user-management');
        $user = User::findOrFail($id);
        $user->load(['organization', 'branch', 'roles']);

        return view('user-management.show', compact('user'));
    }

    public function edit($id)
    {
        $this->authorize('update-user-management');

        $user = User::findOrFail($id);
        $organizations = Organization::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $user->load('roles');

        return view('user-management.edit', compact('user', 'organizations', 'branches', 'roles'));
    }

    public function update(UpdateUserManagementRequest $request, $id)
    {
        $this->authorize('update-user-management');

        $user = User::findOrFail($id);
        $this->service->update($user, $request->validated());

        return redirect()
            ->route('user-management.show', $user)
            ->with('success', 'User updated successfully.');
    }

    public function toggleStatus($id)
    {
        $this->authorize('update-user-management');

        $user = User::findOrFail($id);
        $this->service->toggleStatus($user);

        return redirect()
            ->route('user-management.index')
            ->with('success', 'User status updated successfully.');
    }

    public function resetPassword($id)
    {
        $this->authorize('update-user-management');

        $user = User::findOrFail($id);
        $password = $this->service->resetPassword($user);

        return redirect()
            ->route('user-management.show', $user)
            ->with('success', "Password reset. New password: {$password}");
    }
}
