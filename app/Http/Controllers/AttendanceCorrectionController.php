<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceAdjustment;
use Illuminate\Http\Request;

class AttendanceCorrectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['employee.branch', 'employee.department', 'creator'])
            ->orderByDesc('date');

        if ($search = $request->input('search')) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->where('date', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->where('date', '<=', $dateTo);
        }

        if ($source = $request->input('source')) {
            $query->where('source', $source);
        }

        $attendances = $query->paginate(20)->withQueryString();

        return view('attendances.corrections', compact('attendances'));
    }

    public function edit(Attendance $attendance)
    {
        $attendance->load(['employee.branch', 'employee.department']);

        return view('attendances.correction-edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after_or_equal:clock_in',
            'status' => 'required|in:present,absent,late,half-day,remote',
            'reason' => 'required|string|max:500',
        ]);

        $oldClockIn = $attendance->clock_in;
        $oldClockOut = $attendance->clock_out;
        $oldStatus = $attendance->status;

        $attendance->update([
            'clock_in' => $validated['clock_in'] ?? $attendance->clock_in,
            'clock_out' => $validated['clock_out'] ?? $attendance->clock_out,
            'status' => $validated['status'],
            'hours_worked' => $this->calculateHours($validated['clock_in'] ?? $attendance->clock_in, $validated['clock_out'] ?? $attendance->clock_out),
            'created_by' => auth()->id(),
        ]);

        AttendanceAdjustment::create([
            'organization_id' => $attendance->organization_id,
            'attendance_id' => $attendance->id,
            'employee_id' => $attendance->employee_id,
            'reason' => $validated['reason'],
            'new_clock_in' => $validated['clock_in'] ?? $oldClockIn,
            'new_clock_out' => $validated['clock_out'] ?? $oldClockOut,
            'new_status' => $validated['status'],
            'status' => 'approved',
            'requested_by' => auth()->id(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('attendances.corrections.index')
            ->with('success', 'Attendance record corrected successfully.');
    }

    private function calculateHours(?string $clockIn, ?string $clockOut): float
    {
        if (! $clockIn || ! $clockOut) {
            return 0;
        }

        $start = strtotime($clockIn);
        $end = strtotime($clockOut);
        if ($end <= $start) {
            $end += 86400;
        }

        return round(($end - $start) / 3600, 2);
    }
}
