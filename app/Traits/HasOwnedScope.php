<?php
declare(strict_types=1);

namespace App\Traits;

use App\Models\User;
use App\Helpers\SchemaHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

trait HasOwnedScope
{
    public function scopeOwned(Builder $query): Builder
    {
        $user = auth()->user();

        if (!$user) {
            return $this->handleGuestScope($query);
        }

        if ($user->hasAnyRole(['super_admin', 'administrator'])) {
            return $query;
        }

        $model = $query->getModel();
        $table = $model->getTable();
        $companyId = self::resolveCompanyId($user);

        if ($companyId && SchemaHelper::hasColumn($table, 'company_id')) {
            return $query->where("{$table}.company_id", $companyId);
        }

        if (SchemaHelper::hasColumn($table, 'user_id')) {
            return $query->where("{$table}.user_id", $user->id);
        }

        return $query->whereRaw('0 = 1');
    }

    protected function handleGuestScope(Builder $query): Builder
    {
        return in_array(SoftDeletes::class, class_uses_recursive($query->getModel()))
            ? $query->whereNull('deleted_at')
            : $query->whereRaw('0 = 1');
    }

    protected function resolveCompanyId(User $user): ?string
    {
        return $user->emp?->company_id
            ?? $user->pic?->company_id
            ?? $user->companies
            ?? null;
    }

}
