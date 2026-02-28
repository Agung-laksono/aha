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
        ActivityLog::create([
            'user_id' => auth()->id() ?? 1, // Fallback to ID 1 if not auth
            'team_id' => auth()->user()?->current_team_id,
            'action' => $action,
            'description' => $action . ' ' . class_basename($model) . ': ' . ($model->nama ?? $model->nomor_nota ?? $model->id),
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'properties' => !empty($model->getChanges()) ? json_encode($model->getChanges()) : null,
        ]);
    }
}
