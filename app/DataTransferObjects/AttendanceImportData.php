<?php

namespace App\DataTransferObjects;

readonly class AttendanceImportData
{
    public function __construct(
        public ?int $employeeId,
        public string $employeeCode,
        public string $employeeName,
        public string $date,
        public ?string $clockIn,
        public ?string $clockOut,
        public ?string $status,
        public string $source,
        public int $rowNumber,
    ) {}

    public static function fromArray(array $data, int $rowNumber): self
    {
        return new self(
            employeeId: $data['employee_id'] ?? null,
            employeeCode: (string) ($data['employee_code'] ?? ''),
            employeeName: (string) ($data['employee_name'] ?? ''),
            date: $data['date'] ?? '',
            clockIn: $data['clock_in'] ?? null,
            clockOut: $data['clock_out'] ?? null,
            status: $data['status'] ?? null,
            source: $data['source'] ?? 'import',
            rowNumber: $rowNumber,
        );
    }

    public function toArray(): array
    {
        return [
            'employee_id' => $this->employeeId,
            'employee_code' => $this->employeeCode,
            'employee_name' => $this->employeeName,
            'date' => $this->date,
            'clock_in' => $this->clockIn,
            'clock_out' => $this->clockOut,
            'status' => $this->status,
            'source' => $this->source,
            'row_number' => $this->rowNumber,
        ];
    }
}
