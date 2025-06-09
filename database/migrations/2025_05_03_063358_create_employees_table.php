<?php
declare(strict_types=1);

use App\Enums\Employee\PositionEmpEnum;
use App\Enums\Employee\StatusEmpEnum;
use App\Enums\Employee\TypeEmpEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('employee_id')->unique()->nullable();
            $table->string('name');
            $table->string('gender')->nullable()->default('laki-laki');
            $table->string('birth_place')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->date('date_hired');
            $table->string('position')->default(PositionEmpEnum::Tecknician->value)->comment('technician');
            $table->string('type')->nullable()->default(TypeEmpEnum::EMP->value)->comment('employee, freelancer');;
            $table->string('cv')->nullable();
            $table->string('photo')->nullable();
            $table->string('hourly_rate')->nullable();
            $table->string('contract')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('website')->nullable();
            $table->string('custom_link')->nullable();

            $table->string('status')->default(StatusEmpEnum::ACTIVE->value)->comment('active, inactive');
            $table->longText('description')->nullable();

            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->softDeletesWithBlameable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
