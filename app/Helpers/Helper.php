<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Models\User;
use App\Models\Company;
use App\Models\Contact;

class Helper
{
    public static function getAvailableUsersForPIC(?int $excludeUserId = null)
    {
        $usedUserIds = Contact::query()
            ->when($excludeUserId, fn($q) => $q->where('user_id', '!=', $excludeUserId))
            ->whereNull('deleted_at')
            ->pluck('user_id')
            ->filter()
            ->values();

        return User::whereNotIn('id', $usedUserIds)
            ->get()
            ->mapWithKeys(fn($user) => [$user->id => "{$user->name} ({$user->email})"]);
    }

    /**
     * Get available companies by role.
     *
     * @param array $roles
     * @param int|null $excludeCompanyId
     * @return array
     */
    public static function getAvailableCompanyByRole(array $roles, ?int $excludeCompanyId = null): array
    {
        $query = Company::query();

        if ($excludeCompanyId) {
            $query->where('id', '!=', $excludeCompanyId);
        }
        $query->orderBy('name', 'asc');

        // Add logic to filter companies based on roles if needed
        return $query->pluck('name', 'id')->toArray();
    }

    public static function getAvailableUsersByRole(string|array $roles, int|string|null $excludeUserId = null)
    {
        $excludeUserId = $excludeUserId ? (int) $excludeUserId : null;

        return User::availableForPICWithRole($roles, $excludeUserId)
            ->get()
            ->mapWithKeys(fn($user) => [$user->id => "{$user->name} ~ {$user->email} ~ {$user->roles()->pluck('name')->implode(', ')}"]);
    }
}