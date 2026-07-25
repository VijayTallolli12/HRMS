<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\MissingPunch;
use Illuminate\Http\Request;

class MissingPunchController extends Controller
{
    public function index(Request $request)
    {
        $query = MissingPunch::with(['employee.branch', 'employee.department', 'resolver']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        } else {
            $query->where('status', 'pending');
        }

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

        $missingPunches = $query->orderByDesc('date')->paginate(20)->withQueryString();

        $pendingCount = MissingPunch::where('status', 'pending')->count();

        return view('missing-punches.index', compact('missingPunches', 'pendingCount'));
    }

    public function show(MissingPunch $missingPunch)
    {
        $missingPunch->load(['employee.branch', 'employee.department', 'resolver']);
        $attendance = Attendance::where('employee_id', $missingPunch->employee_id)
            ->where('date', $missingPunch->date)
            ->first();

        return view('missing-punches.show', compact('missingPunch', 'attendance'));
    }

    public function resolve(Request $request, MissingPunch $missingPunch)
    {
        $validated = $request->validate([
            'correct_time' => 'required|date_format:H:i',
            'resolution_notes' => 'nullable|string|max:500',
        ]);

        $field = $missingPunch->punch_type === 'missing_in' ? 'clock_in' : 'clock_out';

        $attendance = Attendance::where('employee_id', $missingPunch->employee_id)
            ->where('date', $missingPunch->date)
            ->first();

        if ($attendance) {
            $attendance->update([$field => $validated['correct_time']]);

            if ($attendance->clock_in && $attendance->clock_out) {
                $start = strtotime($attendance->clock_in);
                $end = strtotime($attendance->clock_out);
                if ($end <= $start) {
                    $end += 86400;
                }
                $attendance->update([
                    'hours_worked' => round(($end - $start) / 3600, 2),
                ]);
            }
        }

        $missingPunch->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolution_notes' => $validated['resolution_notes'],
            'resolved_at' => now(),
        ]);

        return redirect()->route('missing-punches.index')
            ->with('success', 'Missing punch resolved successfully.');
    }

    public function dismiss(Request $request, MissingPunch $missingPunch)
    {
        $missingPunch->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolution_notes' => $request->input('resolution_notes', 'Dismissed by administrator'),
            'resolved_at' => now(),
        ]);

        return redirect()->route('missing-punches.index')
            ->with('success', 'Missing punch dismissed.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'punch_type' => 'required|in:missing_in,missing_out',
            'notes' => 'nullable|string|max:500',
        ]);

        MissingPunch::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'date' => $validated['date'],
                'punch_type' => $validated['punch_type'],
            ],
            [
                'status' => 'pending',
                'detected_by' => 'manual',
            ]
        );

        return back()->with('success', 'Missing punch flagged successfully.');
    }
}
