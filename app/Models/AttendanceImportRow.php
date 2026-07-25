<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceImportRow extends Model
{
    protected $fillable = [
        'import_batch_id',
        'row_number',
        'employee_id',
        'employee_code',
        'employee_name',
        'date',
        'clock_in',
        'clock_out',
        'status',
        'errors',
        'processed_at',
    ];

    protected $casts = [
        'errors' => 'array',
        'processed_at' => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(AttendanceImportBatch::class, 'import_batch_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function isValid(): bool
    {
        return $this->status === 'valid';
    }

    public function isInvalid(): bool
    {
        return $this->status === 'invalid';
    }

    public function isDuplicate(): bool
    {
        return $this->status === 'duplicate';
    }

    public function getErrorSummary(): string
    {
        if (! $this->errors) {
            return '';
        }

        return implode(', ', $this->errors);
    }
}
