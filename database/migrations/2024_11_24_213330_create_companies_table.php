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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('default_currency_id')->nullable();
            $table->unsignedBigInteger('default_language_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('fiscal_country')->default('KE'); // Default is Kenya
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('reference')->nullable();
            $table->boolean('personal_company')->default(true);
            $table->string('domain_name')->nullable();
            $table->string('website_url')->nullable();
            $table->boolean('is_self_hosted')->default(false); // Si le domaine est hébergé par le kover
            $table->boolean('enabled')->default(true);
            $table->boolean('is_onboarded')->default(false);
            $table->enum('status', ['active', 'suspended', 'banished'])->default('active');
            // Company Information
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('phone_2')->unique()->nullable();
            $table->text('address')->nullable();
            $table->string('website')->unique()->nullable();
            $table->text('city')->nullable();
            $table->text('industry')->nullable();
            $table->string('size')->nullable();
            $table->string('primary_interest')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('company_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('property_id')->nullable();
            $table->string('email');
            $table->string('role')->nullable();
            $table->string('token')->unique();
            $table->timestamp('expire_at')->nullable();
            $table->timestamps();

            $table->unique(['email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('company_invitations');
    }
};
