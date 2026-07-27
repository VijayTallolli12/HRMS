<x-app-layout>
    <x-page-header title="Edit Attendance Adjustment" description="Update adjustment status or rejection reason." icon="adjustments-horizontal">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendance-adjustments.index') }}" wire:navigate>Attendance Adjustments</a>
            <span class="breadcrumb-separator">/</span>
            <span>Edit</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('attendance-adjustments.update', $adjustment) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label for="status" class="label">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="select-field" required>
                            <option value="pending" {{ old('status', $adjustment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('status', $adjustment->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ old('status', $adjustment->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                    <div>
                        <label for="rejection_reason" class="label">Rejection Reason</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" class="textarea-field">{{ old('rejection_reason', $adjustment->rejection_reason) }}</textarea>
                        <x-input-error :messages="$errors->get('rejection_reason')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('attendance-adjustments.show', $adjustment) }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Update Adjustment</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
