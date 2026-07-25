<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public static function set(string $key, mixed $value, ?string $group = null): static
    {
        $payload = is_array($value) ? $value : ['raw' => $value];

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $payload, 'group' => $group]
        );
    }
}
