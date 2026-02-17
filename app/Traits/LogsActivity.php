<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logToDatabase($model, 'created');
        });

        static::updated(function ($model) {
            self::logToDatabase($model, 'updated');
        });

        static::deleted(function ($model) {
            self::logToDatabase($model, 'deleted');
        });
    }

    protected static function logToDatabase($model, $event)
    {
        // Hindari logging jika dijalankan dari seeder atau console tanpa user login (opsional)
        // Tapi untuk keamanan, kita catat semua kalau bisa.
        // Jika tidak ada user login (misal job background), user_id null.

        $oldValues = null;
        $newValues = null;

        if ($event === 'updated') {
            $oldValues = $model->getOriginal();
            $newValues = $model->getChanges();
        } elseif ($event === 'created') {
            $newValues = $model->getAttributes();
        } elseif ($event === 'deleted') {
            $oldValues = $model->getAttributes();
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
