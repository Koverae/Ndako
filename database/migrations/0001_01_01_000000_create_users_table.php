<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('current_company_id')->default(0);

            $table->string('avatar')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->rememberToken();

            // Property
            $table->unsignedBigInteger('current_property_id')->nullable();

            // Preferences
            $table->unsignedBigInteger('language_id')->nullable();
            $table->string('timezone')->default('eat');

            $table->integer('onboarding_step')->default(0);
            $table->boolean('onboarding_completed')->default(false);

            // Connection & Security
            $table->enum('identity', ['unverified', 'pending', 'verified'])->default('unverified');
            $table->string('social_id')->nullable();
            $table->string('social_type')->nullable();
            $table->string('password');
            $table->timestamp('password_updated_at')->nullable();
            $table->boolean('two_factor_enabled')->default(true);
            $table->string('two_factor_code')->nullable();
            $table->enum('two_factor_second_step', ['email', 'phone', 'access-key', 'authentificator', 'emergency-code'])->default('email');
            $table->dateTime('two_factor_expires_at')->nullable();
            $table->enum('status', ['confirmed', 'never-connected'])->default('never-connected');
            $table->boolean('is_active')->default(true);
            $table->string('last_login_ip')->nullable();// Add a nullable string field to store the IP address from which the user last logged in
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
