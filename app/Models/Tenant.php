<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'domain',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
