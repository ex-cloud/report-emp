<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasUuid;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
