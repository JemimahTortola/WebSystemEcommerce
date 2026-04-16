<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            AuditLog::logCreated($model, $model->getAttributes());
        });

        static::updating(function ($model) {
            $changes = $model->getChanges();
            $oldValues = [];
            foreach ($changes as $key => $value) {
                if ($key !== 'updated_at') {
                    $oldValues[$key] = $model->getOriginal($key);
                }
            }
            AuditLog::logUpdated($model, $oldValues, $changes);
        });

        static::deleting(function ($model) {
            AuditLog::logDeleted($model, $model->getAttributes());
        });
    }
}
