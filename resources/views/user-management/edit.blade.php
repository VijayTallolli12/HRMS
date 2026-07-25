<x-app-layout>
    <x-page-header title="Edit User" icon="heroicon-o-pencil">
        <x-secondary-button tag="a" href="{{ route('user-management.show', $user) }}" wire:navigate>
            <x-heroicon name="heroicon-o-arrow-left" class="h-4 w-4" />
            Back
        </x-secondary-button>
    </x-page-header>

    <div class="card p-6">
        <form action="{{ route('user-management.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="role" value="Role" />
                    <select id="role" name="role" class="select-field mt-1 block w-full" required>
                        <option value="">Select Role</option>
                        <option value="branch-admin" {{ old('role', $user->role) === 'branch-admin' ? 'selected' : '' }}>Branch Admin</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="organization_id" value="Organization" />
                    <select id="organization_id" name="organization_id" class="select-field mt-1 block w-full">
                        <option value="">Select Organization</option>
                        @foreach($organizations as $organization)
                            <option value="{{ $organization->id }}" {{ old('organization_id', $user->organization_id) == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                        @endforeach
                    </select>
                    @error('organization_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="branch_id" value="Branch" />
                    <select id="branch_id" name="branch_id" class="select-field mt-1 block w-full">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="select-field mt-1 block w-full" required>
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                <x-secondary-button tag="a" href="{{ route('user-management.show', $user) }}" wire:navigate>Cancel</x-secondary-button>
                <x-primary-button type="submit">Update User</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
