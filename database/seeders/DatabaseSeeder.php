<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\PermissionSeeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            HybridSeeder::class,
        ]);

        
    }
}
