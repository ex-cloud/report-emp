<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HideSuperAdminUnlessCurrent
{
    /**
     * Scope untuk menyembunyikan user dengan role super_admin
     * kecuali jika user yang login juga super_admin.
     */
    public function scopeHideSuperAdminUnlessCurrent(Builder $query): Builder
    {
        if (!auth()->user()?->hasRole('super_admin')) {
            return $query->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'super_admin');
            });
        }

        return $query;
    }
}
