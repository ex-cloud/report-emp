<?php
declare(strict_types=1);

use App\Enums\User\StatusUserEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('username')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('status')->default(StatusUserEnum::PENDING->value);
            $table->boolean('is_active')->default(false);
            $table->string('avatar_url')->nullable();
            $table->text('description')->nullable();

            $table->softDeletesWithBlameable();
            $table->rememberToken();
            $table->timestamp('self_updated_at')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => 'Andiansyah',
            'username' => 'andiansyah',
            'phone' => '081946702356',
            'email' => 'andiansyah@kdua.net',
            'password' => Hash::make('Semangatku@25'),
            'status' => 'active',
            'is_active' => true,
        ]);

        Schema::create('password_reset_tokens', static function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', static function (Blueprint $table): void {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
