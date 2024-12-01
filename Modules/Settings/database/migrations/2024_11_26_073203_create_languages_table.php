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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name')->nullable();
            $table->string('icon')->nullable();
            $table->string('locale_code')->nullable();
            $table->string('iso_code')->nullable();
            $table->string('url_code')->nullable();
            $table->enum('direction', ['left_to_right', 'right_to_left'])->nullable();
            $table->string('separator_format')->nullable();
            $table->string('decimal_separator')->nullable();
            $table->string('thousand_separator')->nullable();
            $table->string('date_format')->nullable();
            $table->string('time_format')->nullable();
            $table->enum('first_day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])->default('monday'); //First day of the week
            $table->boolean('is_active')->default(false);
            $table->boolean('is_archive')->default(false);
            $table->boolean('is_reference')->default(false);
            
            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
