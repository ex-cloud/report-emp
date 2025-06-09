<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
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
        // Semua role yang akan dibuat
        $roles = [
            'super_admin',
            'administrator',
            'noc',
            'user',
            'guest',
            'vendor',
            'pic',
            'mitra',
            'supplier',
        ];

        // Generate role
        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName],
                [
                    'id' => Str::uuid(), // Tambahkan ini agar UUID diisi
                    'guard_name' => 'web',
                ]
            );
        }

        // Assign Super Admin
        $user = User::where('email', 'andiansyah@kdua.net')->first();
        if ($user) {
            $superAdmin = Role::findByName('super_admin');
            $user->assignRole($superAdmin);
            $superAdmin->syncPermissions(Permission::all());
        }

        // Atur permission spesifik berdasarkan role
        $this->assignRolePermissions();
    }

    protected function assignRolePermissions(): void
    {
        // Administrator: semua akses penuh
        $adminPermissions = Permission::all();
        Role::findByName('administrator')->syncPermissions($adminPermissions);

        // NOC: hanya bisa view company & contact
        $nocPermissions = Permission::whereIn('name', [
            'view_company',
            'view_contact',
        ])->get();
        Role::findByName('noc')->syncPermissions($nocPermissions);

        // PIC: hanya bisa akses contact miliknya sendiri (batasi via policy)
        $picPermissions = Permission::whereIn('name', [
            'view_contact',
            'update_contact',
        ])->get();
        Role::findByName('pic')->syncPermissions($picPermissions);
        
        // Vendor: hanya bisa akses contact miliknya sendiri (batasi via policy)
        $vendorPermissions = Permission::whereIn('name', [
            'view_contact',
            'update_contact',
        ])->get();
        Role::findByName('vendor')->syncPermissions($vendorPermissions);

        // Mitra: hanya bisa akses contact miliknya sendiri (batasi via policy)
        $mitraPermissions = Permission::whereIn('name', [
            'view_contact',
            'update_contact',
        ])->get();
        Role::findByName('mitra')->syncPermissions($mitraPermissions);

        // Supplier: hanya bisa akses contact miliknya sendiri (batasi via policy)
        $supplierPermissions = Permission::whereIn('name', [
            'view_contact',
            'update_contact',
        ])->get();
        Role::findByName('supplier')->syncPermissions($supplierPermissions);

        // User: hanya bisa akses contact miliknya sendiri (batasi via policy)
        $userPermissions = Permission::whereIn('name', [
            'view_contact',
            'update_contact',
        ])->get();
        Role::findByName('user')->syncPermissions($userPermissions);

        // Guest: hanya bisa view dashboard
        $guestPermissions = Permission::whereIn('name', [
            'view_dashboard',
        ])->get();
        Role::findByName('guest')->syncPermissions($guestPermissions);
    }
}
