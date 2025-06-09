<?php

declare(strict_types=1);

namespace App\Traits;
    
use App\Helpers\SchemaHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

trait Blameable
{
    public static function bootBlameable(): void
    {
        static::creating(function ($model) {
            $user = Auth::user();

            if ($user && !self::isSelfAction($model, $user->id)) {
                self::setBlameFields($model, 'created', $user);
                self::setBlameFields($model, 'updated', $user);
            }
        });

        static::updating(function ($model) {
            $user = Auth::user();

            if ($user && !self::isSelfAction($model, $user->id)) {
                self::setBlameFields($model, 'updated', $user);
            }
        });

        static::deleting(function ($model) {
            $user = Auth::user();

            if ($user && !self::isSelfAction($model, $user->id)) {
                $model->forceFill([
                    'deleted_by' => SchemaHelper::hasColumn($model->getTable(), 'deleted_by') ? $user->id : $model->deleted_by,
                    'deleted_ip' => SchemaHelper::hasColumn($model->getTable(), 'deleted_ip') ? request()->ip() : $model->deleted_ip,
                    'deleted_user_agent' => SchemaHelper::hasColumn($model->getTable(), 'deleted_user_agent') ? request()->userAgent() : $model->deleted_user_agent,
                    'deleted_at' => SchemaHelper::hasColumn($model->getTable(), 'deleted_at') ? now() : $model->deleted_at, // Jika soft delete, set deleted_at
                ])->saveQuietly();
            }
        });

        static::restoring(function ($model) {
            if (SchemaHelper::hasColumn($model->getTable(), 'deleted_by')) {
                $model->deleted_by = null;
            }
            if (SchemaHelper::hasColumn($model->getTable(), 'deleted_ip')) {
                $model->deleted_ip = null;
            }
            if (SchemaHelper::hasColumn($model->getTable(), 'deleted_user_agent')) {
                $model->deleted_user_agent = null;
            }
        });
    }

    protected static function setBlameFields($model, string $prefix, $user): void
    {
        $table = $model->getTable();

        if (SchemaHelper::hasColumn($table, "{$prefix}_by")) {
            $model->{"{$prefix}_by"} ??= $user->id;
        }

        if (SchemaHelper::hasColumn($table, "{$prefix}_ip")) {
            $model->{"{$prefix}_ip"} ??= request()->ip();
        }

        if (SchemaHelper::hasColumn($table, "{$prefix}_user_agent")) {
            $model->{"{$prefix}_user_agent"} ??= request()->userAgent();
        }
    }

    protected static function isSelfAction($model, $userId): bool
    {
        return $model instanceof \App\Models\User && $model->id === $userId;
    }

    // === Relasi ===
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }

    // === Scopes ===
    public function scopeCreatedBy(Builder $query, $userId): Builder
    {
        return $query->where('created_by', $userId);
    }

    public function scopeUpdatedBy(Builder $query, $userId): Builder
    {
        return $query->where('updated_by', $userId);
    }

    public function scopeDeletedBy(Builder $query, $userId): Builder
    {
        return $query->where('deleted_by', $userId);
    }
}
// === End of File ===