<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class HybridSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat 1 Company utama
        $company = Company::factory()->create([
            'name' => 'PT. Kdua',
            'slug' => 'k2net',
            'address' => 'Jl. Raya No. 1',
            'phone' => '1234567890',
            'email' => 'info@k2net.id',
            'website' => 'https://k2net.id',
        ]);

        // 2. Assign setiap user email andiansyah@kdua.net ke company K2Net

        $user = User::where('email', 'andiansyah@kdua.net')->first();
        if ($user) {
            $company = Company::where('slug', 'k2net')->first();
            if ($company) {
                $user->companies()->syncWithoutDetaching($company);
            }
        }

        // 4. Buat role dan assign ke user
        $roles = ['administrator', 'pic'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 5. Buat company dan employee jika belum ada
        if (Company::count() < 11) {
            Company::factory()->count(10)->create();
        }

        if (Employee::count() === 0) {
            Employee::factory()->count(20)->create();
        }

        $companies = Company::all();
        $employees = Employee::all();

        // 6. Assign setiap employee ke 1–3 company
        $employees->each(function (Employee $employee) use ($companies) {
            if ($employee->companies()->count() === 0) {
                $employee->companies()->syncWithoutDetaching(
                    collect($companies->random(rand(1, 3)))
                        ->pluck('id')
                        ->map(fn($id) => (string) $id)
                        ->values()
                        ->all()
                );
            }
        });

        // 7. Assign setiap employee ke 1 user berdasarkan email
        $employees->each(function (Employee $employee) use ($companies) {
            $user = User::firstOrCreate(
                ['email' => $employee->email],
                [
                    'name' => $employee->name,
                    'password' => Hash::make('password'),
                ]
            );

            $employee->user()->associate($user)->save();
        });
    }
}
