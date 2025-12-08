<?php
namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::logAction('create', $model);
        });

        static::updated(function ($model) {
            self::logAction('update', $model);
        });

        static::deleted(function ($model) {
            self::logAction('delete', $model);
        });
    }

    protected static function logAction(string $action, $model)
    {
        if (!auth()->check()) {
            return;
        }

        $oldValues = $action === 'update' ? $model->getOriginal() : null;
        $newValues = $action === 'delete' ? null : $model->getAttributes();

        // Remove sensitive fields
        if ($oldValues) {
            $oldValues = array_diff_key($oldValues, array_flip(['password', 'remember_token']));
        }
        if ($newValues) {
            $newValues = array_diff_key($newValues, array_flip(['password', 'remember_token']));
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => self::getActionDescription($action, $model),
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
        ]);
    }

    protected static function getActionDescription(string $action, $model): string
    {
        $modelName = class_basename($model);
        
        switch ($action) {
            case 'create':
                return "Created new {$modelName}: " . self::getModelIdentifier($model);
            case 'update':
                return "Updated {$modelName}: " . self::getModelIdentifier($model);
            case 'delete':
                return "Deleted {$modelName}: " . self::getModelIdentifier($model);
            default:
                return "Performed {$action} on {$modelName}: " . self::getModelIdentifier($model);
        }
    }

    protected static function getModelIdentifier($model): string
    {
        if (method_exists($model, 'getAuditIdentifier')) {
            return $model->getAuditIdentifier();
        }

        if (isset($model->name)) {
            return $model->name;
        }

        if (isset($model->title)) {
            return $model->title;
        }

        if (isset($model->email)) {
            return $model->email;
        }

        return 'ID: ' . $model->getKey();
    }

    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'model');
    }
}
