<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHolidayRequest;
use App\Http\Requests\UpdateHolidayRequest;
use App\Models\Holiday;
use App\Models\Organization;
use App\Services\HolidayService;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function __construct(private readonly HolidayService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Holiday::class);

        $organizations = Organization::orderBy('name')->get();
        $organizationId = $request->input('organization_id');

        $holidays = $organizationId
            ? $this->service->paginateByOrganization($organizationId, $request->integer('per_page', 15), $request->input('search'))
            : $this->service->paginate($request->integer('per_page', 15), $request->input('search'));

        return view('holidays.index', compact('holidays', 'organizations', 'organizationId'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Holiday::class);

        $organizations = Organization::orderBy('name')->get();

        return view('holidays.create', compact('organizations'))
            ->with('selectedOrgId', $request->input('organization_id'));
    }

    public function store(StoreHolidayRequest $request)
    {
        $this->authorize('create', Holiday::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $holiday = $this->service->create($data);

        return redirect()
            ->route('holidays.show', $holiday)
            ->with('success', 'Holiday created successfully.');
    }

    public function show(Holiday $holiday)
    {
        $this->authorize('view', $holiday);
        $holiday->load('organization');

        return view('holidays.show', compact('holiday'));
    }

    public function edit(Holiday $holiday)
    {
        $this->authorize('update', $holiday);
        $organizations = Organization::orderBy('name')->get();

        return view('holidays.edit', compact('holiday', 'organizations'));
    }

    public function update(UpdateHolidayRequest $request, Holiday $holiday)
    {
        $this->authorize('update', $holiday);
        $this->service->update($holiday, $request->validated());

        return redirect()
            ->route('holidays.show', $holiday)
            ->with('success', 'Holiday updated successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $this->authorize('delete', $holiday);
        $this->service->delete($holiday);

        return redirect()
            ->route('holidays.index')
            ->with('success', 'Holiday deleted successfully.');
    }
}
