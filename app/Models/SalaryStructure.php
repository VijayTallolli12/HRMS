<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryStructure extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'organization_id',
        'basic_salary',
        'currency',
        'pay_frequency',
        'effective_from',
        'effective_to',
        'is_active',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_active' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function components()
    {
        return $this->hasMany(EmployeeSalaryComponent::class, 'employee_id', 'employee_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
