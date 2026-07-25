<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceTemplateExport;
use App\Imports\AttendanceImport;
use App\Models\AttendanceImportBatch;
use App\Services\AttendanceImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceImportController extends Controller
{
    public function __construct(
        private readonly AttendanceImportService $importService,
    ) {}

    public function index(Request $request)
    {
        $batches = $this->importService->getBatchHistory(
            $request->user()->organization_id ?? 0,
        );

        if ($request->user()->isSuperAdmin()) {
            $batches = AttendanceImportBatch::with(['importer', 'branch'])
                ->latest()
                ->paginate(15);
        }

        return view('attendances.import-history', compact('batches'));
    }

    public function create()
    {
        return view('attendances.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:xlsx,xls,csv,txt',
            'organization_id' => 'required|exists:organizations,id',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        $batch = $this->importService->createBatch(
            organizationId: $request->organization_id,
            branchId: $request->branch_id,
            filename: $file->getClientOriginalName(),
            fileType: $extension,
            importedBy: Auth::id(),
        );

        if (in_array($extension, ['xlsx', 'xls'])) {
            $data = Excel::toCollection(new AttendanceImport(Auth::id()), $file)->first()->toArray();
        } else {
            $data = $this->parseCsvOrTxt($file, $extension);
        }

        $rows = [];
        foreach ($data as $index => $row) {
            if ($index === 0 && $this->isHeaderRow($row)) {
                continue;
            }
            $rows[] = array_merge((array) $row, ['row_number' => count($rows) + 1]);
        }

        $this->importService->processRows($batch, $rows);

        return redirect()->route('attendances.import.preview', $batch)
            ->with('success', 'File uploaded. Please review the preview before importing.');
    }

    public function preview(AttendanceImportBatch $batch)
    {
        $rows = $this->importService->getPreview($batch);
        $stats = $this->importService->getStats($batch);

        return view('attendances.import-preview', compact('batch', 'rows', 'stats'));
    }

    public function commit(AttendanceImportBatch $batch)
    {
        if ($batch->valid_rows === 0) {
            return back()->with('error', 'No valid rows to import.');
        }

        $result = $this->importService->commit($batch);

        return redirect()->route('attendances.import.results', $batch)
            ->with('success', "Successfully imported {$result['processed']} attendance records.");
    }

    public function results(AttendanceImportBatch $batch)
    {
        $stats = $this->importService->getStats($batch);

        return view('attendances.import-results', compact('batch', 'stats'));
    }

    public function template()
    {
        return Excel::download(new AttendanceTemplateExport, 'attendance_import_template.xlsx');
    }

    public function downloadInvalid(AttendanceImportBatch $batch)
    {
        $invalidRows = $this->importService->getInvalidRows($batch);

        $csvContent = "Row,Employee Code,Date,Clock In,Clock Out,Status,Errors\n";
        foreach ($invalidRows as $row) {
            $csvContent .= implode(',', [
                $row->row_number,
                $row->employee_code,
                $row->date,
                $row->clock_in ?? '',
                $row->clock_out ?? '',
                $row->status,
                '"'.($row->getErrorSummary()).'"',
            ])."\n";
        }

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"invalid_rows_batch_{$batch->id}.csv\"",
        ]);
    }

    private function parseCsvOrTxt($file, string $extension): array
    {
        $delimiter = $extension === 'txt' ? "\t" : ',';
        $handle = fopen($file->getPathname(), 'r');
        $data = [];

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $data[] = $row;
        }

        fclose($handle);

        return $data;
    }

    private function isHeaderRow(array $row): bool
    {
        $firstVal = strtolower(trim((string) ($row[0] ?? '')));
        $headerKeywords = ['employee', 'emp', 'code', 'id', 'name', 'date', 'attendance'];

        return in_array($firstVal, $headerKeywords) || str_contains($firstVal, 'employee');
    }
}
