<x-app-layout>
    <x-page-header title="Import Attendance" icon="arrow-up-tray">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.dashboard') }}" wire:navigate>Attendance</a>
            <span class="breadcrumb-separator">/</span>
            <span>Import</span>
        </x-slot>
        <x-slot name="actions">
            <a href="{{ route('attendances.import.template') }}" class="btn-secondary" wire:navigate>
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                Download Template
            </a>
        </x-slot>
    </x-page-header>

    <div class="card max-w-3xl">
        <div class="card-body">
            <form action="{{ route('attendances.import.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label for="file" class="label">Upload Attendance File <span class="text-red-500">*</span></label>
                    <div class="flex items-center justify-center w-full">
                        <label for="file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-card cursor-pointer hover:bg-gray-50 transition-colors {{ $errors->has('file') ? 'border-red-300 bg-red-50' : 'border-gray-300' }}">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                                <p class="text-body text-gray-600 mb-1"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-caption text-gray-400">.xlsx, .xls, .csv, or .txt (max 10MB)</p>
                            </div>
                            <input id="file" name="file" type="file" class="hidden" accept=".xlsx,.xls,.csv,.txt" required />
                        </label>
                    </div>
                    @error('file') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    <div id="file-info" class="mt-2 text-caption text-gray-500 hidden"></div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="organization_id" class="label">Organization <span class="text-red-500">*</span></label>
                        <select id="organization_id" name="organization_id" class="select-field" required>
                            <option value="">Select organization</option>
                            @foreach(\App\Models\Organization::orderBy('name')->get() as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                        @error('organization_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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

                <div class="bg-sky-50 border border-sky-200 rounded-card p-4 mb-6">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-sky-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                        <div class="text-body text-sky-800">
                            <p class="font-medium mb-1">Expected File Format</p>
                            <p>Your file should contain columns: <code class="bg-sky-100 px-1.5 py-0.5 rounded text-caption">employee_code</code>, <code class="bg-sky-100 px-1.5 py-0.5 rounded text-caption">date</code>, <code class="bg-sky-100 px-1.5 py-0.5 rounded text-caption">clock_in</code>, <code class="bg-sky-100 px-1.5 py-0.5 rounded text-caption">clock_out</code>, <code class="bg-sky-100 px-1.5 py-0.5 rounded text-caption">status</code></p>
                            <p class="mt-1">Status values: present, absent, late, half-day, remote</p>
                            <p class="mt-1">Use <strong>Employee Code</strong> (employee_id field) to match records. Download the template for an example.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-100">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                        Upload & Preview
                    </button>
                    <a href="{{ route('attendances.dashboard') }}" class="btn-ghost" wire:navigate>Cancel</a>
                </div>
            </form>
        </div>
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
