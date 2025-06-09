<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Employee;
use App\Enums\User\StatusUserEnum;
use App\Observers\EmployeeObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Spatie\Permission\PermissionRegistrar;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blueprint::macro('blameable', function () {
            /** @var Blueprint $this */
            $tableName = $this->getTable() ?? 'unknown_table';

            // Untuk table 'users' pakai foreign key manual
            if ($tableName === 'users') {
                $this->foreignUuid('created_by')->nullable()->index();
                $this->string('created_ip', 45)->nullable();
                $this->string('created_user_agent')->nullable();

                $this->foreignUuid('updated_by')->nullable()->index();
                $this->string('updated_ip', 45)->nullable();
                $this->string('updated_user_agent')->nullable();

                $this->foreignUuid('deleted_by')->nullable()->index();
                $this->string('deleted_ip', 45)->nullable();
                $this->string('deleted_user_agent')->nullable();

                // Tambahkan constraint foreign key manual
                $this->foreign('created_by', 'fk_users_created_by')
                    ->references('id')->on('users')->nullOnDelete();

                $this->foreign('updated_by', 'fk_users_updated_by')
                    ->references('id')->on('users')->nullOnDelete();

                $this->foreign('deleted_by', 'fk_users_deleted_by')
                    ->references('id')->on('users')->nullOnDelete();
            } else {
                // 🚩 Fix di sini ➔ kasih constraint name sendiri, biar tidak auto duplikat
                $this->foreignUuid('created_by')
                    ->nullable()
                    ->index()
                    ->constrained('users', 'id', "fk_{$tableName}_created_by")
                    ->nullOnDelete();

                $this->string('created_ip', 45)->nullable();
                $this->string('created_user_agent')->nullable();

                $this->foreignUuid('updated_by')
                    ->nullable()
                    ->index()
                    ->constrained('users', 'id', "fk_{$tableName}_updated_by")
                    ->nullOnDelete();

                $this->string('updated_ip', 45)->nullable();
                $this->string('updated_user_agent')->nullable();

                $this->foreignUuid('deleted_by')
                    ->nullable()
                    ->index()
                    ->constrained('users', 'id', "fk_{$tableName}_deleted_by")
                    ->nullOnDelete();

                $this->string('deleted_ip', 45)->nullable();
                $this->string('deleted_user_agent')->nullable();
            }
        });

        Blueprint::macro('softDeletesWithBlameable', function () {
            /** @var Blueprint $this */
            $this->softDeletes();
            $this->blameable();
        });

        $registrar = app(PermissionRegistrar::class);
        $registrar->forgetCachedPermissions();

        $registrar->setPermissionClass(\App\Models\Permission::class);
        $registrar->setRoleClass(\App\Models\Role::class);


        Employee::observe(EmployeeObserver::class);
        Gate::policy(\App\Models\Role::class, 'App\Policies\RolePolicy');
        Gate::policy(User::class, 'App\Policies\UserPolicy');
        Gate::policy(Company::class, 'App\Policies\CompanyPolicy');
        Gate::policy(Contact::class, 'App\Policies\ContactPolicy');
        Gate::policy(Employee::class, 'App\Policies\EmployeePolicy');


        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                return true; // super_admin bisa akses semuanya
            }

            if ($user->status !== StatusUserEnum::ACTIVE || !$user->is_active) {
                return false; // user nonaktif diblokir
            }

            return null;
        });
        
        Gate::guessPolicyNamesUsing(function (string $modelClass) {
            return str_replace('Models', 'Policies', $modelClass) . 'Policy';
        });
    
    }
}