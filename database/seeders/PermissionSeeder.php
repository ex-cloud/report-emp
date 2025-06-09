<?php
declare(strict_types=1);
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    protected array $models = [
        'Company',
        'Employee',
        'Contact',
        'User',
    ];

    protected array $defaultActions = [
        'view',
        'view_any',
        'create',
        'update',
        'delete',
        'delete_any',
        'force_delete',
        'force_delete_any',
        'restore',
        'restore_any',
        'replicate',
    ];
    public function run(): void
    {

        foreach ($this->models as $model) {
            foreach ($this->defaultActions as $action) {
                $permissionName = "{$action}_" . Str::kebab(Str::snake($model));

                // Create permission if it doesn't exist
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    [
                        'id' => Str::uuid(), // Tambahkan ini agar UUID diisi
                        'guard_name' => 'web',
                    ]
            );
            }
        }

        // Tambahkan juga permission untuk dashboard jika diperlukan
        Permission::firstOrCreate(
            ['name' => 'view_dashboard'],
            [
                'id' => Str::uuid(), // Tambahkan ini agar UUID diisi
                'guard_name' => 'web',
            ]
        );
    }
}
