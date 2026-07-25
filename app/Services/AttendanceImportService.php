<?php

namespace App\Services;

use App\DataTransferObjects\AttendanceImportData;
use App\Models\Attendance;
use App\Models\AttendanceImportBatch;
use App\Models\AttendanceImportRow;
use App\Models\Employee;
use App\Models\MissingPunch;
use Illuminate\Support\Collection;

class AttendanceImportService
{
    public function createBatch(
        int $organizationId,
        ?int $branchId,
        string $filename,
        string $fileType,
        int $importedBy,
    ): AttendanceImportBatch {
        return AttendanceImportBatch::create([
            'organization_id' => $organizationId,
            'branch_id' => $branchId,
            'filename' => $filename,
            'file_type' => $fileType,
            'imported_by' => $importedBy,
            'status' => 'pending',
        ]);
    }

    public function processRows(AttendanceImportBatch $batch, array $dataRows): void
    {
        $employeeCache = $this->buildEmployeeCache($batch->organization_id);
        $existingAttendanceCache = $this->buildAttendanceCache($batch->organization_id);
        $validCount = 0;
        $invalidCount = 0;
        $duplicateCount = 0;

        foreach ($dataRows as $index => $rowData) {
            $rowNumber = $rowData['row_number'] ?? ($index + 1);
            $data = AttendanceImportData::fromArray($rowData, $rowNumber);
            $errors = [];
            $employeeId = null;
            $employeeName = '';

            $employeeCode = trim($data->employeeCode);
            $employeeId = $employeeCache[$employeeCode] ?? null;

            if (! $employeeId && $data->employeeId) {
                $employeeId = $data->employeeId;
            }

            if ($employeeId) {
                $emp = Employee::find($employeeId);
                $employeeName = $emp ? trim($emp->first_name.' '.$emp->last_name) : '';
            } else {
                $errors[] = 'Employee not found with code: '.$employeeCode;
            }

            if (empty($data->date) || ! strtotime($data->date)) {
                $errors[] = 'Invalid or missing date';
            } elseif ($employeeId && isset($existingAttendanceCache[$employeeId][$data->date])) {
                $duplicateCount++;
                AttendanceImportRow::create([
                    'import_batch_id' => $batch->id,
                    'row_number' => $rowNumber,
                    'employee_id' => $employeeId,
                    'employee_code' => $employeeCode,
                    'employee_name' => $employeeName,
                    'date' => $data->date,
                    'clock_in' => $data->clockIn,
                    'clock_out' => $data->clockOut,
                    'status' => 'duplicate',
                    'errors' => ['Attendance record already exists for this date'],
                ]);

                continue;
            }

            if ($data->clockIn && $data->clockOut) {
                if (strtotime($data->clockOut) <= strtotime($data->clockIn)) {
                    $errors[] = 'Clock out must be after clock in';
                }
            }

            if ($data->status && ! in_array($data->status, ['present', 'absent', 'late', 'half-day', 'remote'])) {
                $errors[] = 'Invalid status: '.$data->status;
            }

            $finalStatus = $data->status;
            if (! $finalStatus) {
                if ($data->clockIn && $data->clockOut) {
                    $hoursWorked = $this->calculateHoursWorked($data->clockIn, $data->clockOut);
                    $finalStatus = $hoursWorked >= 4 ? 'present' : 'half-day';
                } else {
                    $finalStatus = 'present';
                }
            }

            if ($errors) {
                $invalidCount++;
                $status = 'invalid';
            } else {
                $validCount++;
                $status = 'valid';
            }

            AttendanceImportRow::create([
                'import_batch_id' => $batch->id,
                'row_number' => $rowNumber,
                'employee_id' => $employeeId,
                'employee_code' => $employeeCode,
                'employee_name' => $employeeName,
                'date' => $data->date,
                'clock_in' => $data->clockIn,
                'clock_out' => $data->clockOut,
                'status' => $status,
                'errors' => $errors ?: null,
            ]);
        }

        $batch->update([
            'total_rows' => $validCount + $invalidCount + $duplicateCount,
            'valid_rows' => $validCount,
            'invalid_rows' => $invalidCount,
            'duplicate_rows' => $duplicateCount,
            'status' => 'preview',
        ]);
    }

    public function commit(AttendanceImportBatch $batch): array
    {
        $batch->update(['status' => 'processing']);

        $validRows = $batch->rows()->where('status', 'valid')->get();
        $processed = 0;
        $created = [];

        foreach ($validRows as $row) {
            $hoursWorked = 0;
            $overtimeHours = 0;
            $lateMinutes = 0;

            if ($row->clock_in && $row->clock_out) {
                $hoursWorked = $this->calculateHoursWorked($row->clock_in, $row->clock_out);
                $overtimeHours = max(0, $hoursWorked - 8);
            }

            $attendance = Attendance::create([
                'organization_id' => $batch->organization_id,
                'employee_id' => $row->employee_id,
                'date' => $row->date,
                'clock_in' => $row->clock_in,
                'clock_out' => $row->clock_out,
                'status' => $row->status === 'valid' ? 'present' : $row->status,
                'hours_worked' => $hoursWorked,
                'overtime_hours' => $overtimeHours,
                'late_minutes' => $lateMinutes,
                'source' => 'import',
                'import_batch_id' => $batch->id,
                'created_by' => $batch->imported_by,
            ]);

            $row->update([
                'status' => 'processed',
                'processed_at' => now(),
            ]);

            $this->detectMissingPunchForAttendance($attendance);

            $processed++;
            $created[] = $attendance;
        }

        $batch->update([
            'processed_rows' => $processed,
            'status' => 'completed',
        ]);

        return [
            'processed' => $processed,
            'total' => $validRows->count(),
            'batch' => $batch,
        ];
    }

    public function getPreview(AttendanceImportBatch $batch): Collection
    {
        return $batch->rows()->orderBy('row_number')->get();
    }

    public function getStats(AttendanceImportBatch $batch): array
    {
        return [
            'total' => $batch->total_rows,
            'valid' => $batch->valid_rows,
            'invalid' => $batch->invalid_rows,
            'duplicate' => $batch->duplicate_rows,
            'processed' => $batch->processed_rows,
        ];
    }

    public function getInvalidRows(AttendanceImportBatch $batch): Collection
    {
        return $batch->rows()->where('status', 'invalid')->orderBy('row_number')->get();
    }

    public function getBatchHistory(int $organizationId, int $perPage = 15)
    {
        return AttendanceImportBatch::where('organization_id', $organizationId)
            ->with(['importer', 'branch'])
            ->latest()
            ->paginate($perPage);
    }

    private function detectMissingPunchForAttendance(Attendance $attendance): void
    {
        if ($attendance->clock_in && ! $attendance->clock_out) {
            MissingPunch::updateOrCreate(
                ['employee_id' => $attendance->employee_id, 'date' => $attendance->date, 'punch_type' => 'missing_out'],
                [
                    'original_value' => $attendance->clock_in,
                    'status' => 'pending',
                    'detected_by' => 'import',
                ]
            );
        } elseif (! $attendance->clock_in && $attendance->clock_out) {
            MissingPunch::updateOrCreate(
                ['employee_id' => $attendance->employee_id, 'date' => $attendance->date, 'punch_type' => 'missing_in'],
                [
                    'original_value' => $attendance->clock_out,
                    'status' => 'pending',
                    'detected_by' => 'import',
                ]
            );
        } elseif (! $attendance->clock_in && ! $attendance->clock_out) {
            MissingPunch::updateOrCreate(
                ['employee_id' => $attendance->employee_id, 'date' => $attendance->date, 'punch_type' => 'missing_in'],
                ['status' => 'pending', 'detected_by' => 'import']
            );
            MissingPunch::updateOrCreate(
                ['employee_id' => $attendance->employee_id, 'date' => $attendance->date, 'punch_type' => 'missing_out'],
                ['status' => 'pending', 'detected_by' => 'import']
            );
        }
    }

    private function calculateHoursWorked(string $clockIn, string $clockOut): float
    {
        $start = strtotime($clockIn);
        $end = strtotime($clockOut);

        if ($end <= $start) {
            $end += 86400;
        }

        return round(($end - $start) / 3600, 2);
    }

    private function buildEmployeeCache(int $organizationId): array
    {
        return Employee::where('organization_id', $organizationId)
            ->where('status', 'active')
            ->pluck('id', 'employee_id')
            ->toArray();
    }

    private function buildAttendanceCache(int $organizationId): array
    {
        $cache = [];
        Attendance::where('organization_id', $organizationId)
            ->select('employee_id', 'date')
            ->get()
            ->each(function ($record) use (&$cache) {
                $cache[$record->employee_id][$record->date] = true;
            });

        return $cache;
    }
}
