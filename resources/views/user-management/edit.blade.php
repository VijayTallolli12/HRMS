<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Edit {{ $user->name }}"
            icon="pencil-square"
            description="Update user profile and access settings."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('user-management.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">User Management</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Edit {{ $user->name }}</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('user-management.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="name" value="Name *" />
                                <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name', $user->name)" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="email" value="Email *" />
                                <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full" :value="old('email', $user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="role" value="Role *" />
                                <select id="role" name="role" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Role</option>
                                    <option value="branch-admin" {{ old('role', $user->role) === 'branch-admin' ? 'selected' : '' }}>Branch Admin</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="status" value="Status *" />
                                <select id="status" name="status" class="select-field mt-1.5 block w-full" required>
                                    <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                        <option value="{{ $organization->id }}" {{ old('organization_id', $user->organization_id) == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('organization_id')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="branch_id" value="Branch" />
                                <select id="branch_id" name="branch_id" class="select-field mt-1.5 block w-full">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('branch_id')" class="mt-1.5" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('user-management.show', $user) }}" class="btn-secondary" wire:navigate>Cancel</a>
                        <x-primary-button type="submit" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            Update User
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
