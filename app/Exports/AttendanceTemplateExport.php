<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return collect([
            ['EMP001', '2026-07-01', '09:00', '18:00', 'present'],
            ['EMP002', '2026-07-01', '09:15', '18:30', 'late'],
            ['EMP003', '2026-07-01', '', '', 'absent'],
        ]);
    }

    public function headings(): array
    {
        return ['Employee Code', 'Date', 'Clock In', 'Clock Out', 'Status'];
    }
}
