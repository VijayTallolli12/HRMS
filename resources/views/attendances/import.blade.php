<x-app-layout>
    <x-page-header title="Import Attendance" icon="arrow-up-tray" :breadcrumb="[
        ['label' => 'Attendance Dashboard', 'route' => 'attendances.dashboard'],
        ['label' => 'Import'],
    ]">
        <x-slot:actions>
            <a href="{{ route('attendances.import.template') }}" class="btn-secondary" wire:navigate>
                <x-heroicon name="document-arrow-down" class="w-4 h-4" />
                Download Template
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="card p-6 max-w-3xl">
        <form action="{{ route('attendances.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Upload Attendance File</label>
                <div class="flex items-center justify-center w-full">
                    <label for="file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-xl cursor-pointer hover:bg-gray-50 transition-colors {{ $errors->has('file') ? 'border-rose-300 bg-rose-50' : 'border-gray-300' }}">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <x-heroicon name="arrow-up-tray" class="w-10 h-10 text-gray-400 mb-3" />
                            <p class="text-sm text-gray-600 mb-1"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-400">.xlsx, .xls, .csv, or .txt (max 10MB)</p>
                        </div>
                        <input id="file" name="file" type="file" class="hidden" accept=".xlsx,.xls,.csv,.txt" required />
                    </label>
                </div>
                @error('file') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                <div id="file-info" class="mt-2 text-sm text-gray-500 hidden"></div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="organization_id" class="label">Organization <span class="text-rose-500">*</span></label>
                    <select id="organization_id" name="organization_id" class="select-field" required>
                        <option value="">Select organization</option>
                        @foreach(\App\Models\Organization::orderBy('name')->get() as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </select>
                    @error('organization_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="branch_id" class="label">Branch (optional)</label>
                    <select id="branch_id" name="branch_id" class="select-field">
                        <option value="">All branches</option>
                        @foreach(\App\Models\Branch::orderBy('name')->get() as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex gap-3">
                    <x-heroicon name="information-circle" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" />
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Expected File Format</p>
                        <p>Your file should contain columns: <code class="bg-blue-100 px-1 rounded">employee_code</code>, <code class="bg-blue-100 px-1 rounded">date</code>, <code class="bg-blue-100 px-1 rounded">clock_in</code>, <code class="bg-blue-100 px-1 rounded">clock_out</code>, <code class="bg-blue-100 px-1 rounded">status</code></p>
                        <p class="mt-1">Status values: present, absent, late, half-day, remote</p>
                        <p class="mt-1">Use <strong>Employee Code</strong> (employee_id field) to match records. Download the template for an example.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">
                    <x-heroicon name="arrow-up-tray" class="w-4 h-4" />
                    Upload & Preview
                </button>
                <a href="{{ route('attendances.dashboard') }}" class="btn-secondary" wire:navigate>Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.getElementById('file').addEventListener('change', function(e) {
            const info = document.getElementById('file-info');
            if (e.target.files.length) {
                info.textContent = 'Selected: ' + e.target.files[0].name + ' (' + (e.target.files[0].size / 1024).toFixed(1) + ' KB)';
                info.classList.remove('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>
