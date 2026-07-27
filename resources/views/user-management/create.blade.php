<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Create User"
            icon="user-plus"
            description="Add a new user to your organization."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('user-management.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">User Management</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Create</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('user-management.store') }}" method="POST">
                    @csrf

                    <div class="space-y-5">
                        {{-- Account Section --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="name" value="Name *" />
                                <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="email" value="Email *" />
                                <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="password" value="Password *" />
                                <x-text-input id="password" name="password" type="password" class="mt-1.5 block w-full" required />
                                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="password_confirmation" value="Confirm Password *" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1.5 block w-full" required />
                            </div>
                        </div>

                        {{-- Role & Assignment Section --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="role" value="Role *" />
                                <select id="role" name="role" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Role</option>
                                    <option value="branch-admin" {{ old('role') === 'branch-admin' ? 'selected' : '' }}>Branch Admin</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="status" value="Status *" />
                                <select id="status" name="status" class="select-field mt-1.5 block w-full" required>
                                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-1.5" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="organization_id" value="Organization" />
                                <select id="organization_id" name="organization_id" class="select-field mt-1.5 block w-full">
                                    <option value="">Select Organization</option>
                                    @foreach($organizations as $organization)
                                        <option value="{{ $organization->id }}" {{ old('organization_id') == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('organization_id')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="branch_id" value="Branch" />
                                <select id="branch_id" name="branch_id" class="select-field mt-1.5 block w-full">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('branch_id')" class="mt-1.5" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('user-management.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                        <x-primary-button type="submit" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                            Create User
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
