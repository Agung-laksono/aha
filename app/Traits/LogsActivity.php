<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            static::recordActivity($model, 'Created');
        });

        static::updated(function (Model $model) {
            static::recordActivity($model, 'Updated');
        });

        static::deleted(function (Model $model) {
            static::recordActivity($model, 'Deleted');
        });
    }

    protected static function recordActivity(Model $model, string $action)
    {
        $description = $action . ' ' . class_basename($model);

        // Cek jika model punya kustom deskripsi
        if (method_exists($model, 'logDescription')) {
            $description = $model->logDescription($action);
        } else {
            $description .= ': ' . ($model->nama ?? $model->nomor_nota ?? $model->id);
        }

        ActivityLog::create([
            'user_id' => auth()->id() ?? 1,
            'team_id' => auth()->user()?->current_team_id,
            'action' => $action,
            'description' => $description,
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'properties' => $action === 'Created'
                ? ['attributes' => $model->getAttributes()]
                : (!empty($model->getChanges()) ? ['attributes' => $model->getChanges(), 'old' => array_intersect_key($model->getOriginal(), $model->getChanges())] : null),
        ]);
    }
}
