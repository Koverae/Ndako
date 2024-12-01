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
        Schema::create('module_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->enum('status', ['published', 'on_test', 'disabled'] )->default('published');
            $table->enum('type', ['app_category', 'industry_category'] )->default('app_category');
            $table->timestamps();
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('module_category_id')->nullable();
            $table->string('parent_slug')->nullable();
            $table->string('slug');
            $table->string('short_name')->nullable();
            $table->string('description');
            $table->string('app_group')->nullable();
            $table->string('version')->nullable();
            $table->string('author')->nullable();
            $table->string('icon')->nullable();
            $table->enum('type', ['installable', 'fundamental', 'dependent'])->default('installable');
            $table->boolean('is_default')->default(false);
            $table->string('link')->nullable();
            $table->boolean('enabled')->default(true);
            $table->string('path')->nullable();
            $table->string('navbar_id')->nullable();

            $table->foreign('module_category_id')->references('id')->on('module_categories')->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('installed_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('module_slug');
            $table->boolean('is_approved')->default(true);
            $table->unsignedBigInteger('installed_by')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->foreign('installed_by')->references('id')->on('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_categories');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('installed_modules');
    }
};
