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
        // Property Types
        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('property_type', ['single', 'multi', 'custom'])->default('multi');
            $table->enum('property_type_group', ['residential', 'commercial', 'hospitality', 'mixed'])->default('hospitality'); // Optional categorization
            $table->json('attributes')->nullable(); // Customizable attributes
            $table->json('default_settings')->nullable(); // Default attribute values

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Properties
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_type_id')->nullable();
            $table->enum('invoicing_type', ['rental', 'rate'])->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('address')->nullable();
            // $table->json('amenities')->nullable();
            $table->enum('status', ['active', 'inactive', 'under-maintenance'])->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Floors
        Schema::create('property_floors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_id');
            $table->string('name')->comment('e.g., "Ground Floor", "First Floor"');
            $table->tinyText('description')->nullable();
            $table->boolean('is_available')->default(true);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Units Types
        Schema::create('property_unit_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('pricing_id')->nullable();
            $table->string('name')->comment("e.g., 'Premium Room', 'Cluster Room', 'Twin Room'");
            $table->tinyText('description')->nullable();
            $table->integer('capacity')->default(1);
            $table->string('size')->nullable();
            $table->json('unit_features')->nullable();
            $table->json('unit_utilities')->nullable();
            $table->boolean('is_available')->default(true);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Unit Type Pricings
        Schema::create('property_unit_type_pricings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_unit_type_id')->nullable();
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('lease_term_id')->nullable();
            $table->string('name')->unique()->nullable()->comment('e.g., "Premium Room Price", "Twin Room Price"');
            $table->decimal('price', 12, 2);
            $table->decimal('discounted_price', 12, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_per_night')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
        // Units
        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('property_unit_type_id');
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->string('name');
            $table->tinyText('description')->nullable();
            $table->json('attributes')->nullable();
            $table->json('default_setttings')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_cleaned')->default(true);
            $table->string('status')->default('vacant');
            $table->timestamp('last_cleaned_at')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Unit Statuses
        Schema::create('unit_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name')->comment('e.g., "Occupied", "Vacant", "Under Maintenance"');
            $table->tinyText('description')->nullable();
            $table->boolean('is_housekeeping')->default(false);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Utilities
        Schema::create('utilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->tinyText('description')->nullable();
            $table->boolean('is_included')->default(true)->comment("e.g., cost per kWh, cubic meter, or flat rate");
            $table->enum('billing_cycle', ['weekly', 'monthly', 'quarterly ', 'yearly'])->default('monthly');
            $table->decimal('price_per_unit', $precision = 12, $scale = 2)->default(0);

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Amenities
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->tinyText('description')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Features
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('category')->nullable(); // Optional: Categorize features

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        // Properties Amenities
        Schema::create('property_amenities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('amenity_id');

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
        });
        // Properties Utilities
        Schema::create('property_utilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('property_unit_type_id')->nullable();
            $table->unsignedBigInteger('utility_id');

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
        });
        // Features
        Schema::create('property_features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('feature_id');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('property_unit_type_id')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
            $table->timestamps();
        });
        // LeaseTerm
        Schema::create('lease_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name')->comment('e.g., "Monthly", "Annual"');
            $table->tinyText('description')->nullable();
            $table->integer('duration_in_days')->default(0);
            $table->boolean('is_default')->default(false);

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
        Schema::table('property_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('properties', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_floors', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_unit_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_unit_type_pricings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_units', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('unit_statuses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('utilities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_utilities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('amenities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_amenities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('features', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('property_features', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('lease_terms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('unit_pricelists', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
