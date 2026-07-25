<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            $model->logAudit('created', null, $model->toArray());
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            $original = collect($dirty)->mapWithKeys(fn ($value, $key) => [$key => $model->getOriginal($key)])->toArray();
            $model->logAudit('updated', $original, $dirty);
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted', $model->toArray(), null);
        });
    }

    public function logAudit(string $event, ?array $oldValues, ?array $newValues): void
    {
        AuditLog::create([
            'tenant_id' => $this->tenant_id ?? optional(auth()->user())->tenant_id,
            'user_id' => optional(auth()->user())->id,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->getKey(),
            'event' => $event,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => request()->url(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
