<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

trait RoleFiltering
{
    /**
     * Ambil daftar role yang boleh ditampilkan berdasarkan mapping role login.
     *
     * @param array<string, array<string>> $allowedRolesPerRole
     *     Contoh:
     *     [
     *         'super_admin' => ['super_admin', 'administrator', 'user'],
     *         'administrator' => ['administrator', 'user'],
     *         'default' => ['user']
     *     ]
     */
    public static function getFilteredRoles(array $allowedRolesPerRole): array
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        foreach ($allowedRolesPerRole as $loginRole => $allowedRoles) {
            if ($loginRole !== 'default' && $user->hasRole($loginRole)) {
                return Role::whereIn('name', $allowedRoles)->pluck('name', 'id')->toArray();
            }
        }

        // Jika tidak cocok role apa pun, fallback ke 'default' jika ada
        return isset($allowedRolesPerRole['default'])
            ? Role::whereIn('name', $allowedRolesPerRole['default'])->pluck('name', 'id')->toArray()
            : [];
    }
}
