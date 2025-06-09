<?php
declare(strict_types=1);

use App\Enums\Company\StatusCompany;
use App\Enums\Company\TypeCompany;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('address', 150)->nullable();
            $table->string('city', 150)->nullable();
            $table->string('region', 150)->nullable();
            $table->string('country', 150)->nullable();
            $table->string('postal_code', 25)->nullable();
            $table->string('website')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('custom_link')->nullable();
            $table->string('logo', 1024)->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default(StatusCompany::Pending->value); // status kerjasama (aktif/non aktif)
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('type')->default(TypeCompany::Mitra->value); // vendor/mitra

            $table->softDeletesWithBlameable();
            $table->timestamps();
        });

        Schema::create('company_user', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->cascadeOnDelete();

            $table->softDeletesWithBlameable();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
